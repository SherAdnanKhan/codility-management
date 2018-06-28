<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Inform;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class InformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $informs= Inform::all()->sortByDesc('id');
        return view('Admin.Inform.index',compact('informs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']);})->get();
        return view('Admin.Inform.create',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'attendance_date'   => 'required',
            'inform_at'         => 'required',
            'employee'          => 'required',
            'reason'            => 'required',
            'inform_type'       => 'required',
        ]);
        $attendance_date = Carbon::parse($request->attendance_date)->timestamp;
        $inform_at = Carbon::parse($request->inform_at)->timestamp;
        $attendance = Carbon::parse($request->attendance_date);
        $add_day = $attendance->endOfDay()->timestamp;
        $check_attendance = Attendance::whereBetween('check_in_time',[$attendance->startOfDay()->timestamp ,$add_day])->where('user_id',$request->employee)->first();
        $inform= Inform::create([
            'attendance_date'=>$attendance_date,
            'inform_at'      =>$inform_at,
            'user_id'        =>$request->employee,
            'inform_type'    =>$request->inform_type,
            'reason'         =>$request->reason,
            'inform_late'    =>$request->late_informed?true:false,
            'leave_type'     =>$request->leave_type?$request->leave_type:Null

        ]);

        if (!(empty($check_attendance))){
            $user = User::whereId($request->employee)->pluck('checkInTime')->first();
            $compare_time =Carbon::parse($request->inform_at);
            $default_time =Carbon::parse($user);
            $late_inform =$compare_time->greaterThanOrEqualTo($default_time);
                $check_attendance->leave_id         = $inform->id;
                $check_attendance->leave_comment    = $request->reason;
                $check_attendance->informed         = true;
                $check_attendance->late_informed    = $late_inform?true:false;
                $check_attendance->attendance_type  = $request->inform_type =='leave'?'LeaveByAdmin':'inform';
                $check_attendance->update();

        }
        return redirect()->route('inform.index')->with('status', 'Employee Inform Added !');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Inform::findOrFail($id)->first();
        return \response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inform=Inform::findOrFail($id);
        $leave=$inform->leaves?$inform->leaves:Null;
        return view('Admin.Inform.edit',compact('inform','leave'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance_date = Carbon::parse($request->attendance_date)->timestamp;
        $inform_at = Carbon::parse($request->inform_at)->timestamp;
        $inform= Inform::whereId($id)->update([
            'attendance_date'=>$attendance_date,
            'inform_at'      =>$inform_at,
            'inform_type'    =>$request->inform_type,
            'reason'         =>$request->reason,
            'inform_late'    =>$request->late_informed?true:false,
            'leave_type'     =>$request->leave_type?$request->leave_type:Null

        ]);
        return redirect()->route('inform.index')->with('status', 'Employee Inform Updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inform = Inform::whereId($id)->delete();
        return redirect()->route('inform.index')->with('status','Employee Inform Deleted !');
    }
}
