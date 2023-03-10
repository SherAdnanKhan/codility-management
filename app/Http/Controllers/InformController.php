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
use Illuminate\Support\Facades\Session;
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
        $informs= Inform::orderBy('attendance_date', 'desc')->paginate(10);
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
        $user = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']);})->where('abended',false)->get();
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
            'leave_type'        =>'required_if:inform_type,==,leave'
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
                $check_attendance->informed         = true;
                $check_attendance->late_informed    = $late_inform?true:false;
                $check_attendance->attendance_type  = 'inform';
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
        $result = Inform::whereId($id)->first();
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
        if (\Session::get('inform_url') == null) {
         $infom_url = \Session::put('inform_url', url()->previous());

        }
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

        $this->validate($request, [
            'attendance_date' => 'required',
            'inform_at' => 'required',
            'reason' => 'required',
            'inform_type' => 'required',
            'leave_type' => 'required_if:inform_type,==,leave'

        ]);
        $attendance_date = Carbon::parse($request->attendance_date)->timestamp;
        $inform_at = Carbon::parse($request->inform_at)->timestamp;
        $inform = Inform::whereId($id)->update([
            'attendance_date' => $attendance_date,
            'inform_at' => $inform_at,
            'inform_type' => $request->inform_type,
            'reason' => $request->reason,
            'inform_late' => $request->late_informed ? true : false,
            'leave_type' => $request->inform_type == 'leave' ? $request->leave_type : null

        ]);
        $get_inform = Inform::whereId($id)->first();
        $check_attendance = Attendance::whereBetween('check_in_time', [Carbon::parse($request->attendance_date)->startOfDay()->timestamp, Carbon::parse($request->attendance_date)->endOfDay()->timestamp])->where('user_id', $get_inform->user_id)->first();

        if ($check_attendance != null) {
            $check_attendance->late_informed = $get_inform->inform_late;
            $check_attendance->save();
        }
        if (\Session::get('inform_url') != null) {
            $url=\Session::get('inform_url');
            \Session::forget('inform_url');
            return redirect($url);
        } else {

        return redirect()->route('inform.index')->with('status', 'Employee Inform Updated !');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inform = Inform::whereId($id)->first();
        $check_attendance = Attendance::whereBetween('check_in_time',[Carbon::parse($inform->attendance_date)->startOfDay()->timestamp,Carbon::parse($inform->attendance_date)->endOfDay()->timestamp])->where('user_id',$inform->user_id)->first();
        if ($check_attendance != null){
            return redirect()->route('inform.index')->with('status','This Employee have an attendance that have this inform, First you have to delete attendance !');

        }else{
            $inform->delete();
        }
        return redirect()->route('inform.index')->with('status','Employee Inform Deleted !');
    }
    public function search(Request $request){

        $this->validate($request,[
            'start_date' =>'required_if:filter,custom',
            'end_date'  =>'required_if:filter,custom',
        ]);
        if($request->filter == 'custom') {
            $this->start_date = Carbon::parse($request->start_date)->timestamp;
            $this->end_date   = Carbon::parse($request->end_date)->timestamp;
        }else{

            $start_date = Carbon::now();
            $this->start_date=$request->filter=='today'?$start_date->startOfDay()->timestamp:($request->filter == 'week'?$start_date->startOfWeek()->timestamp:($request->filter == 'month'?$start_date->startOfMonth()->timestamp:($request->filter =='year'?$start_date->startOfYear()->timestamp:'')));
            $this->end_date=Carbon::now()->timestamp;
        }
        $name=$request->name?$request->name:null;

        $user=User::where('name','Like','%'.$name.'%')->first();

    if ($name != null) {
    $informs = Inform::whereBetween('attendance_date', [$this->start_date, $this->end_date])->where('user_id', $user != null ? $user->id : '')->orderBy('attendance_date', 'desc')->paginate(10);

    }

    if ($name == null){
    $informs = Inform::whereBetween('attendance_date', [$this->start_date, $this->end_date])->orderBy('attendance_date', 'desc')->paginate(10);

    }

        $informs->withPath("inform?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
        return view('Admin.Inform.index',compact('informs'));

    }
}
