<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Inform;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;
use function PhpParser\filesInDir;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isEmployee()) {
            $attendances = Attendance::whereUserId(Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
            $get_tomorrow = Carbon::today()->endOfDay()->timestamp;
            $get_today = Carbon::today()->timestamp;
            $today = Attendance::whereBetween('check_in_time',[$get_today,$get_tomorrow])->Where('user_id', Auth::id())->first();
            return view('Attendance.index', compact('attendances', 'today'));
        }
        elseif(Auth::user()->isAdmin()){
            $attendances = Attendance::orderBy('id','desc')->paginate(10);
            $users = User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->get();
            return view('Attendance.admin_attendance_index', compact('attendances','users'));
        }
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Attendance.create');
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
            'check_in_time'   => 'required|date',
        ]);

        $check_in_time  = Carbon::parse($request->check_in_time)->timestamp;
        $check_out_time = $request->check_out_time  ? Carbon::parse($request->check_out_time)->timestamp:false;
        $break_interval = $request->break_interval ? Carbon::parse($request->break_interval)->timestamp :false;
        $user = Auth::user()->isEmployee()?User::findOrFail(Auth::id()):User::findOrFail($request->employee);
        $default_time = Carbon::parse($user->checkInTime);
        $attendance_time = Carbon::parse($request->check_in_time);
        $compare_time = $attendance_time->gt($default_time);
        if ($request->check_out_time){
            $out_time = Carbon::parse($request->check_out_time);
            $time_spent = $out_time->diffInRealMinutes($attendance_time);
        }
        else{$time_spent =false;}
        if ($compare_time) {
            $attendance = Carbon::parse($request->check_in_time);
            $add_day = $attendance->endOfDay()->timestamp;
            $status = $compare_time?'late':'check_in';
            $inform = Inform::whereBetween('attendance_date',[$attendance->startOfDay()->timestamp, $add_day])->where('user_id',Auth::user()->isEmployee()?Auth::id():$request->employee)->first();
        }else{$inform=false;
        $status ='check_in';
        }
        if ($request->leave_type){
            $attendance = Attendance::create([
                'check_in_time'     => $check_in_time,
                'check_out_time'    => $check_out_time,
                'break_interval'    => $break_interval,
                'user_id'           => Auth::user()->isEmployee() ? Auth::user()->id : $request->employee,
                'attendance_type'   =>'LeaveByAdmin',
                'leave_id'          => $request->leave_type,
            ]);
            return redirect()->route('attendance.index')->with('status', 'Attendance Mark By Admin!');

        }else {
            $attendance = Attendance::create([
                'check_in_time'     => $check_in_time,
                'check_out_time'    => $check_out_time,
                'break_interval'    => $break_interval,
                'user_id'           => Auth::user()->isEmployee() ? Auth::user()->id : $request->employee,
                'attendance_type'   => $inform ? 'inform' : $status,
                'leave_id'          => $inform ? $inform->id : Null,
                'leave_comment'     => $inform ? $inform->reason : Null,
                'informed'          => $inform ? true : Null,
                'late_informed'     => $inform ? $inform->inform_late : Null,
                'time_spent'        => $time_spent ? $time_spent : Null
            ]);
            return redirect()->route('attendance.index')->with('status', 'Attendance Mark For Today !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $attendance=Attendance::whereId($id)->pluck('id')->first();
        return \response()->json($attendance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('Attendance.edit',compact('attendance'));
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
            'check_out_time'   => 'required',
            'break_interval'   => 'required'
        ]);
        $check_out_time = Carbon::parse($request->check_out_time)->timestamp;
        $break_interval = Carbon::parse($request->break_interval)->timestamp;

        $get_attendance = Attendance::whereId($id)->pluck('check_in_time')->first();
        $check_in_time  = Carbon::parse($get_attendance);
        $out_time =Carbon::parse($request->check_out_time);
        $time_spent = $out_time->diffInHours($check_in_time);

        $attendance = Attendance::whereId($id)->update([
            'check_out_time'        =>  $check_out_time,
            'break_interval'        =>  $break_interval,
            'time_spent'            =>  $time_spent
        ]);
        return redirect()->route('attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance= Attendance::whereId($id)->delete();
        return redirect()->route('attendance.index')->with('status','Employee Attendance Deleted !');
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
        $name=$request->name?$request->name:'';
        if (Auth::user()->isAdmin()) {
            $user = User::whereName($name)->first();
            $attendances = Attendance::whereBetween('check_in_time', [$this->start_date, $this->end_date])->orWhere('user_id', $user != null ? $user->id : '')->paginate(10);
            $attendances->withPath("attendance?filter=year&start_date=$request->start_date=&end_date=$request->end_date&name=$name");
            $users = User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->get();
            return view('Attendance.admin_attendance_index', compact('attendances','users'));
        }
        else{
            $attendances = Attendance::whereBetween('check_in_time', [$this->start_date, $this->end_date])->where('user_id', Auth::user()->id)->paginate(10);
            $attendances->withPath("attendance?filter=year&start_date=$request->start_date=&end_date=$request->end_date&name=$name");
            return view('Attendance.index', compact('attendances'));
        }

    }
}
