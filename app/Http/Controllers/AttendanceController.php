<?php

namespace App\Http\Controllers;
use App\Attendance;
use App\Helper\Helper;
use App\Inform;
use App\User;
use Carbon\Carbon;

use function Composer\Autoload\includeFile;
use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use phpDocumentor\Reflection\Types\Null_;
use function PhpParser\filesInDir;
use App\ZKLib;
use COM;
use Session;
use JWTFactory;

use Tymon\JWTAuth\JWTAuth;

class AttendanceController extends Controller
{
    public $get_employement_month;
    public $days;
    public $total_days_form;
    public $dsn;
    public $public_holiday;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::user()->isEmployee()) {
            $attendances = Attendance::whereUserId(Auth::user()->id)->orderBy('check_in_time', 'desc')->paginate(10);
            $get_tomorrow = Carbon::today()->endOfDay()->timestamp;
            $get_today = Carbon::today()->timestamp;
            $today = Attendance::whereBetween('check_in_time', [$get_today, $get_tomorrow])->Where('user_id', Auth::id())->first();
            return view('Attendance.index', compact('attendances', 'today'));
        } elseif (Auth::user()->isAdmin()) {
            $attendances = Attendance::orderBy('check_in_time', 'desc')->paginate(10);
            $users = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->where('abended', false)->get();
            return view('Attendance.admin_attendance_index', compact('attendances', 'users'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'check_in_time' => 'required|date',
        ]);


        $check_in_time = Carbon::parse($request->check_in_time)->timestamp;
        $check_out_time = $request->check_out_time ? Carbon::parse($request->check_out_time)->timestamp : false;
        if (Auth::user()->isEmployee()) {
            if ($check_in_time > Carbon::now()->timestamp || $check_in_time < Carbon::now()->startOfDay()->timestamp) {
                return redirect()->route('attendance.index')->with('status', 'Attendance Check in Incorrect Please mark Again !');
            } elseif ($check_out_time) {
                if ($check_out_time > Carbon::now()->timestamp || $check_out_time < Carbon::yesterday()->timestamp) {
                    return redirect()->route('attendance.index')->with('status', 'Attendance Check out Incorrect Please mark Again !');

                }
            }
        }
        $break_interval = $request->break_interval ? Carbon::parse($request->break_interval)->timestamp : false;
        $user = Auth::user()->isEmployee() ? User::findOrFail(Auth::id()) : User::findOrFail($request->employee);
        $time_of_attendance=Carbon::parse($request->check_in_time)->startOfDay();
        $user_time=Carbon::parse($user->checkInTime)->addMinutes(15)->format('H:i');
//        dd($user_time);
        $time_explode=explode(':',$user_time);
        $default_time = $time_of_attendance->addHours($time_explode[0])->addMinutes($time_explode[1]);
        $attendance_time = Carbon::parse($request->check_in_time);

        $compare_time = $attendance_time->gt($default_time);
//dd($compare_time);
        if ($request->check_out_time) {
            $time = $request->break_interval ? Carbon::parse($request->break_interval)->format('H:i') : false;
            $explode_time = explode(':', $time);
            $break_time = ($explode_time[0] * 60) + ($explode_time[1]);
            $out_time = Carbon::parse($request->check_out_time);
            $time_spent = $out_time->diffInRealMinutes($attendance_time) - $break_time;
        } else {
            $time_spent = false;
        }
        if ($compare_time) {

            $attendance = Carbon::parse($request->check_in_time);
            $add_day = $attendance->endOfDay()->timestamp;
            $status = $compare_time ? 'late' : 'check_in';
            $inform = Inform::whereBetween('attendance_date', [$attendance->startOfDay()->timestamp, $add_day])->where('user_id', Auth::user()->isEmployee() ? Auth::id() : $request->employee)->first();
        } else {
            $inform = false;
            $status = 'check_in';
        }
        if ($request->leave_type) {
            $attendance = Attendance::create([
                'check_in_time' => $check_in_time,
                'check_out_time' => $check_out_time,
                'break_interval' => $break_interval,
                'user_id' => Auth::user()->isEmployee() ? Auth::user()->id : $request->employee,
                'attendance_type' => 'LeaveByAdmin',
                'leave_id' => $request->leave_type,
            ]);
            return redirect()->route('attendance.index')->with('status', 'Attendance Mark By Admin!');

        } else {
            if ($inform) {
                if ($inform->inform_type == 'LEAVE') {

                    $attendance = Attendance::create([
                        'check_in_time' => $check_in_time,
                        'check_out_time' => null,
                        'break_interval' => null,
                        'user_id' => Auth::user()->isEmployee() ? Auth::user()->id : $request->employee,
                        'attendance_type' => 'check_in',
                        'request_id'       =>null
                    ]);
                    return redirect()->route('attendance.index')->with('status', 'You are on Leave !');
                }
            }

            $attendance = Attendance::create([
                'check_in_time' => $check_in_time,
                'check_out_time' => $check_out_time,
                'break_interval' => $break_interval,
                'user_id' => Auth::user()->isEmployee() ? Auth::user()->id : $request->employee,
                'attendance_type' => $inform ? 'inform' : $status,
                'informed' => $inform ? true : Null,
                'late_informed' => $inform ? $inform->inform_late : Null,
                'time_spent' => $time_spent ? $time_spent : Null
            ]);
            return redirect()->route('attendance.index')->with('status', 'Attendance Marked !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $attendance = Attendance::whereId($id)->pluck('id')->first();
        return \response()->json($attendance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Session::get('attendance_url') == null) {
            \Session::put('attendance_url', url()->previous());

        }
        $attendance = Attendance::whereId($id)->first();
        return view('Attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'check_out_time' => 'required',
            'break_interval' => 'required'
        ]);
        $check = Carbon::now()->timestamp;
        if (Auth::user()->isEmployee() && Carbon::parse($request->check_out_time)->timestamp > $check) {
            return redirect()->route('attendance.index')->with('status', 'Not allowed Checkout time is Incorrect!');
        }


        $check_out_time = Carbon::parse($request->check_out_time);
        $break_interval = Carbon::parse($request->break_interval)->timestamp;
        if (Auth::user()->isEmployee()) {
            if (Carbon::parse($request->check_out_time)->timestamp > Carbon::now()->timestamp || Carbon::parse($request->check_out_time)->timestamp < Carbon::yesterday()->timestamp) {
                return redirect()->route('attendance.index')->with('status', 'Attendance Check out Incorrect Please mark Again !');
            }
        }
        $get_attendance = Attendance::whereId($id)->pluck('check_in_time')->first();
        if (Auth::user()->isAdmin()) {
            if ($request->check_in_time) {
                $get_attendance = $request->check_in_time;
            }
        }

        $check_in_time = Carbon::parse($get_attendance);
        $time = $request->break_interval ? Carbon::parse($request->break_interval)->format('H:i') : false;
        $explode_time = explode(':', $time);
        $break_time = ($explode_time[0] * 60) + ($explode_time[1]);
        $out_time = Carbon::parse($request->check_out_time);
        $time_spent = $check_out_time->diffInRealMinutes($check_in_time) - $break_time;

        $attendance = Attendance::whereId($id)->update([
            'check_out_time' => $check_out_time->timestamp,
            'break_interval' => $break_interval,
            'time_spent' => $time_spent
        ]);
        if (Auth::user()->isAdmin()) {
            $attendance = Attendance::whereId($id)->update([
                'check_in_time' => $check_in_time->timestamp,
                'check_out_time' => $check_out_time->timestamp,
                'break_interval' => $break_interval,
                'time_spent' => $time_spent
            ]);
            if (\Session::get('attendance_url') != null) {
                $url=\Session::get('attendance_url');
                \Session::forget('attendance_url');
                return redirect($url);
            } else {
                return redirect()->route('attendance.index');

            }
        }
        return redirect()->route('attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance = Attendance::whereId($id)->delete();
        return redirect()->back()->with('status', 'Employee Attendance Deleted !');
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required_if:filter,custom',
            'end_date' => 'required_if:filter,custom',

        ]);

        if ($request->filter == 'custom') {
            $this->start_date = Carbon::parse($request->start_date)->timestamp;
            $this->end_date = Carbon::parse($request->end_date)->timestamp;
        } else {
            $start_date = Carbon::now();
            $this->start_date = $request->filter == 'today' ? $start_date->startOfDay()->timestamp : ($request->filter == 'week' ? $start_date->startOfWeek()->timestamp : ($request->filter == 'month' ? $start_date->startOfMonth()->timestamp : ($request->filter == 'year' ? $start_date->startOfYear()->timestamp : '')));
            $this->end_date = Carbon::now()->timestamp;
        }
        $name = $request->name ? $request->name : null;

        if (Auth::user()->isAdmin()) {
            $user = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->where('name','Like','%'.$name.'%')->first();
            if ($name == null) {

                $attendances = Attendance::whereBetween('check_in_time', [$this->start_date, $this->end_date])->orderBy('check_in_time', 'desc')->paginate(10);

            } elseif ($name != null) {

                $attendances = Attendance::whereBetween('check_in_time', [$this->start_date, $this->end_date])->where('user_id', $user != null ? $user->id : '')->orderBy('check_in_time', 'desc')->paginate(10);

            }
            $attendances->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
            $users = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->get();
            return view('Attendance.admin_attendance_index', compact('attendances', 'users'));
        } else {
            $attendances = Attendance::whereBetween('check_in_time', [$this->start_date, $this->end_date])->where('user_id', Auth::user()->id)->orderBy('check_in_time', 'desc')->paginate(10);
            $attendances->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
            $get_tomorrow = Carbon::today()->endOfDay()->timestamp;
            $get_today = Carbon::today()->timestamp;
            $today = Attendance::whereBetween('check_in_time', [$get_today, $get_tomorrow])->Where('user_id', Auth::id())->first();
            return view('Attendance.index', compact('attendances','today'));
        }

    }

    public function getViewAdminReportPage()
    {

        return view('Report.report')->with('status', 'This page is for getting report on your requirements');
    }

    public function makeReportByAdmin(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $get_user = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();


        foreach ($get_user as $user) {
            $start_search_date = $start_date->startOfDay();
            $end_search_date = $end_date->endOfDay();
            if (Carbon::parse($request->start_date)->year == 2018) {
                $total_absent = $user->opening_balance ? $user->opening_balance : 0;
            }else{
                $total_absent=0;
            }
            $current_month_absent = 0;
            $this->public_holiday=0;
            $final_total_leaves=0;
            $names = array('name' => $user->name);
            $collection = collect($names);
            $collection->put('compensatory_leaves',$user->compensatory_leaves);
            //
            $check_employee_dob=Carbon::parse($user->joiningDate);
            if ($check_employee_dob->year == $start_search_date->year ){
                $user_attendance = $user->attendance()->whereBetween('check_in_time', [Carbon::parse($user->joiningDate)->timestamp, $end_search_date->timestamp])->orderBy('id', 'desc')->get();

            }else{
                $user_attendance = $user->attendance()->whereBetween('check_in_time', [$start_search_date->timestamp, $end_search_date->timestamp])->orderBy('id', 'desc')->get();
            }
            //
            if ($user_attendance != null) {
                foreach ($user_attendance as $attendance) {

                    if((Helper::check_leaveby_admin($attendance->getOriginal('attendance_type')) == true ) || ( Helper::check_informed_leave($attendance->getOriginal('attendance_type')) == true )){
//                    if (($attendance->attendance_type == 'Leave Marked By Admin') || ($attendance->attendance_type == 'Informed-Leave Marked By System ')) {
                        $total_absent += 1;

                    }
                    if(Helper::check_uninformed_leave($attendance->getOriginal('attendance_type')) == true){
//                    if ($attendance->attendance_type == 'Absent Marked By System') {
                        $total_absent += 1;
                    }
                    $inform_get =$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp);
                        if ($inform_get != null){
                            if ($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE")
                            {
                                $public_holiday_get=$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->leaves->public_holiday;
                                $this->public_holiday+=$public_holiday_get== true?1:0;

                            }

            }

                }
            }
//            echo ($total_absent.$attendance->user->name);
            $sub_of_public_holiday=abs($this->public_holiday - $total_absent);
            $collection->put('total_absent', $sub_of_public_holiday + $user->abended);
            $now_month = Carbon::now()->month;
            if (!((Carbon::parse($user->joiningDate)->month == $now_month) && (Carbon::parse($user->joiningDate)->year == Carbon::now()->year))) {
                $joiningDate=Carbon::parse($user->joiningDate);
                $this->get_employement_month = Carbon::parse($request->start_date)->diffInMonths(Carbon::parse($request->end_date));

            }
            if (Carbon::parse($user->joiningDate)->month == $now_month && Carbon::parse($user->joiningDate)->year == Carbon::now()->year) {
                $this->get_employement_month = 1;
            }
           //
            if (Carbon::parse($user->joiningDate)->year == $start_search_date->year ){
//                $this->get_employement_month=Carbon::parse($user->joiningDate)->diffInMonths(Carbon::parse($request->end_date));
                $this->get_employement_month=Carbon::parse($request->start_date)->diffInMonths(Carbon::parse($request->end_date));

            }
            //

            if (Carbon::parse($user->joiningDate)->year <= $start_search_date->year) {
                $allowed_absent = abs($this->get_employement_month) ;
            }else{
                $allowed_absent = 0;

            }

            $allowed_absent = abs($this->get_employement_month)+1 ;
            $new=$allowed_absent;
//dd($new*1.5);
//            while ($new >= 1){
//
//                $allowed_absent+=1.5;
//echo "asdf";
//                $new --;
//            }
            $allowed_absent= $new * Helper::leave_cotta();
//var_dump( $allowed_absent);
//dd('ad');

            if ($user->compensatory_leaves >=1){

                $final_total_leaves=$allowed_absent + $user->compensatory_leaves ;
            }

            $collection->put('allowed_absent', $final_total_leaves != false?$final_total_leaves:$allowed_absent);
            $user_details[] = array($collection->all());

        }
//echo($final_total_leaves);
//        dd('adsf');
        if (!empty($user_details)) {
            Session::flash('status', 'Employee Detail of required ERA ');

            return view('Report.report', compact('user_details'));
        } else {
            return redirect()->route('view.admin.report')->with('status', 'Please make sure you have entered correct detail');

        }
    }

    public function monthlyAdminReportPage()
    {

        return view('Report.monthly')->with('status', 'This page is for getting monthly report on your requirements');
    }

    public function makeMonthlyReportByAdmin(Request $request)
    {
        $this->validate($request, [
            'month' => 'required',
        ]);
        $emails = array();
        $names = array();
        $start_of_month = Carbon::parse($request->month . '/1')->startOfMonth();
        $end_of_month = $start_of_month->endOfMonth();
        //Get All Employee
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        foreach ($users as $user_attendance) {
            $total_minutes = 0;
            $sum_lates = 0;
            $sum_leaves = 0;
            $informed_late = 0;
            $absent = 0;
            $names = array('name' => $user_attendance->name);
            $collection = collect($names);
            $this->public_holiday=0;
            $check_attendance = $user_attendance->attendance()->whereBetween('check_in_time', [Carbon::parse($request->month . '/1')->startOfMonth()->timestamp, Carbon::parse($request->month . '/1')->endOfMonth()->timestamp])->first();
            $get_attendance = $user_attendance->attendance()->whereBetween('check_in_time', [Carbon::parse($request->month . '/1')->startOfMonth()->timestamp, Carbon::parse($request->month . '/1')->endOfMonth()->timestamp])->orderBy('id', 'desc')->get();
            //Check employee marked attendance at least one in a  whole week
            if ($check_attendance != null) {

                foreach ($get_attendance as $attendance) {
                    //check if employee have check in time in attendance
                    if ($attendance->check_in_time) {
                        //check if employee have late
                        if (Helper::check_uninformed_late($attendance->getOriginal('attendance_type')) == true){
//                        if ($attendance->attendance_type == 'UnInformed Late') {
                            $sum_lates += 1;
                        }
                        if((Helper::check_leaveby_admin($attendance->getOriginal('attendance_type')) == true ) || ( Helper::check_informed_leave($attendance->getOriginal('attendance_type')) == true )){
//                        if (($attendance->attendance_type == 'Leave Marked By Admin') || ($attendance->attendance_type == 'Informed-Leave Marked By System ')) {
                            $sum_leaves += 1;

                        }
                        if(Helper::check_uninformed_leave($attendance->getOriginal('attendance_type')) == true){

//                        if ($attendance->attendance_type == 'Absent Marked By System') {
                            $absent += 1;
                        }
                        if(Helper::check_informed_late($attendance->getOriginal('attendance_type')) ==true){
//                        if ($attendance->attendance_type == 'Informed') {
                            if ($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, \Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LATE") {
                                $informed_late += 1;
                            }
                        }
                        $inform_get =$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp);
                        if ($inform_get != null){
                            if ($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE")
                            {
                                $public_holiday_get=$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->leaves->public_holiday;
                                $this->public_holiday+=$public_holiday_get== true?1:0;
                            }

                        }
                        //check employee if have schedule task during their attendance check in and checkout time
                        if ($attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, Carbon::parse($attendance->check_out_time)->timestamp)->first() != null) {
                            $get_task = $attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get();
                            foreach ($get_task as $task) {
                                // check if employee task have time
                                if ($task->time_take) {
                                    $explode = explode(':', $task->time_take);
                                    $total_minutes += ($explode[0] * 60) + ($explode[1]);

                                }
                            }
                        }
                    }
                }

                $sub_of_public_holiday=abs($this->public_holiday - $sum_leaves);
                $concatinate = $collection->put('late', $sum_lates);
                $concatinate = $collection->put('leave', $sub_of_public_holiday);
                $concatinate = $collection->put('informed_late', $informed_late);
                $concatinate = $collection->put('absent', $absent);
                $concatinate = $collection->put('email', $user_attendance->email);

                $default_check_in_time = Carbon::parse($user_attendance->checkInTime);
                $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                $break_time = Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("h:i");
                $explode_break_time = explode(':', $break_time);
                $total_break_minutes = ($explode_break_time[0] * 60) + ($explode_break_time[1]);
                $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes;
                //edit
                $get_month_dates = Carbon::parse($request->month . '/1')->endOfMonth()->isSunday();
                if ($get_month_dates == true){
                    $this->days=Carbon::parse($request->month . '/1')->endOfMonth()->day - 2;
                }else{
                    $this->days=Carbon::parse($request->month . '/1')->endOfMonth()->day;

                }
//                dd($this->days);
                $workdays = array();
                $type = CAL_GREGORIAN;
                $month =Carbon::parse($request->month . '/1')->endOfMonth()->month; // Month ID, 1 through to 12.
                $year = Carbon::parse($request->month . '/1')->endOfMonth()->year; // Year in 4 digit 2018 format.
                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                $get_date=Carbon::parse($user_attendance->joiningDate)->month;
                if ($get_date == $month){
                    $start_date=Carbon::parse($user_attendance->joiningDate)->day;
                }else
                {
                    $start_date =1;
                }
                if ($user_attendance->workingDays == 5) {
                    for ($i = $start_date; $i <= $day_count; $i++) {

                        $date = $year . '/' . $month . '/' . $i; //format date
                        $get_name = date('l', strtotime($date)); //get week day
                        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                        //if not a weekend add day to array
                        if ($day_name != 'Sun' && $day_name != 'Sat') {
                            $workdays[] = $i;
                        }

                    }
                } elseif ($user_attendance->workingDays == 6) {
                    for ($i = $start_date; $i <= $day_count; $i++) {

                        $date = $year . '/' . $month . '/' . $i; //format date
                        $get_name = date('l', strtotime($date)); //get week day
                        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                        //if not a weekend add day to array
                        if ($day_name != 'Sun') {
                            $workdays[] = $i;
                        }

                    }
                }
//echo($this->days);
//                dd($workdays);
                $collect = collect($workdays);
                $collect->each(function ($item, $key) {
                    echo($item."</br>");
                    if ($item == $this->days) {
                        $this->total_days_form = $key  ;
                    }
                });
//dd($this->total_days_form);
                $subtract_absent_days = $this->total_days_form + 1 - ($absent + $sum_leaves);
                $total_day_time = $subtract_time * $subtract_absent_days;
                $total_minutes_display=$total_day_time;
//                $division = $total_day_time / 100;
//                $mulitpication = $division * 10;
//                $compensate = $total_day_time - $mulitpication;
                $getlessTime = +($total_day_time - $total_minutes);
                $lessTime = abs($getlessTime);
                if ($total_minutes <=  $total_day_time) {
                    $lessHours = sprintf("%02d:%02d", floor($lessTime / 60), $lessTime % 60);
                    $extraHours = '';

                }else
                {
                    $extraHours = sprintf("%02d:%02d", floor($lessTime / 60), $lessTime % 60);

                    $lessHours = '00:00';

                }
                    $loggedTime = sprintf("%02d:%02d", floor($total_minutes / 60), $total_minutes % 60);
                    $requiredWithoutCompansetionTime = sprintf("%02d:%02d", floor($total_minutes_display / 60), $total_minutes_display % 60);
                    $requiredTime = sprintf("%02d:%02d", floor($total_day_time / 60), $total_day_time % 60);
                    $concatinate = $collection->put('loggedTime', $loggedTime);
                    $concatinate = $collection->put('requiredTime', $requiredTime);
                    $concatinate = $collection->put('lessHours', $lessHours);
                    $concatinate = $collection->put('extraHours', $extraHours);
                    $concatinate = $collection->put('requiredWithoutCompansetionTime', $requiredWithoutCompansetionTime);
                    $concatinate = $collection->put('user_id', $user_attendance->id);
                    $user_name[] = array($concatinate->all());
//                    $names []=array('name'=>$user_attendance->name,'loggedTime'=>$loggedTime,'requiredTime'=>$requiredTime,'lessHours'=>$lessHours);
                    $emails[] = $user_attendance->email;
//                }

            } //Check employee if not marked attendance for whole week get their name and email
            elseif ($check_attendance == null) {

                $default_check_in_time = Carbon::parse($user_attendance->checkInTime);
                $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                $break_time = Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("h:i");
                $explode_break_time = explode(':', $break_time);
                $total_break_minutes = ($explode_break_time[0] * 60) + ($explode_break_time[1]);
                $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes;
                $this->days = Carbon::parse($request->month . '/1')->endOfMonth()->day;
                $workdays = array();
                $type = CAL_GREGORIAN;
                $month = Carbon::parse($request->month . '/1')->month; // Month ID, 1 through to 12.

                $year = Carbon::parse($request->month . '/1')->year; // Year in 4 digit 2018 format.
                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                //loop through all days
                for ($i = 1; $i <= $day_count; $i++) {

                    $date = $year . '/' . $month . '/' . $i; //format date
                    $get_name = date('l', strtotime($date)); //get week day
                    $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                    //if not a weekend add day to array
                    if ($day_name != 'Sun' && $day_name != 'Sat') {
                        $workdays[] = $i;
                    }

                }

                $collect = collect($workdays);
                $collect->each(function ($item, $key) {
//                    dd($item);

                    $this->total_days_form = $key;

                });
                $subtract_absent_days = $this->total_days_form + 1 - ($absent + $sum_leaves);
                $total_day_time = $subtract_time * $subtract_absent_days;
                $total_minutes_display=$total_day_time;
//                $division = $total_day_time / 100;
//                $mulitpication = $division * 10;
//                $compensate = $total_day_time - $mulitpication;
                $getlessTime = +($total_day_time - $total_minutes);
                $lessTime = abs($getlessTime);
                if ($total_minutes <=  $total_day_time) {
                    $lessHours = sprintf("%02d:%02d", floor($lessTime / 60), $lessTime % 60);
                    $extraHours = '';

                }else
                {
                    $extraHours = sprintf("%02d:%02d", floor($lessTime / 60), $lessTime % 60);

                    $lessHours = '00:00';

                }
                $loggedTime = sprintf("%02d:%02d", floor($total_minutes / 60), $total_minutes % 60);
                $requiredWithoutCompansetionTime = sprintf("%02d:%02d", floor($total_minutes_display / 60), $total_minutes_display % 60);
                $requiredTime = sprintf("%02d:%02d", floor($total_day_time / 60), $total_day_time % 60);
                $absent = 0;
                $concatinate = $collection->put('absent', $absent);
                $concatinate = $collection->put('loggedTime', $loggedTime);
                $concatinate = $collection->put('requiredTime', $requiredTime);
                $concatinate = $collection->put('lessHours', $lessHours);
                $concatinate = $collection->put('email', $user_attendance->email);
                $concatinate = $collection->put('requiredWithoutCompansetionTime', $requiredWithoutCompansetionTime);
                $concatinate = $collection->put('user_id', $user_attendance->id);
                $user_name[] = array($concatinate->all());
                // $names []=array('name'=>$user_attendance->name,'loggedTime'=>$loggedTime,'requiredTime'=>$requiredTime,'lessHours'=>$lessHours);
                $emails[] = $user_attendance->email;

            }
        }
        if (isset($user_name)) {
            $user_detail = $user_name;

        } else {
            $user_detail = null;
        }
        return view('Report.monthly', compact('user_detail'));
    }

    public function taskInaccuracyReportPage()
    {

        return view('Report.inaccuracy');
    }

    public function makeInaccuracyReportByAdmin(Request $request)
    {
        $this->validate($request, [
            'month' => 'required',
        ]);
        $names = array();
        $start_of_month = Carbon::parse($request->month . '/1')->startOfMonth();
        $end_of_month = $start_of_month->endOfMonth();
        //Get All Employee
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        foreach ($users as $user_attendance) {

            $names = array('name' => $user_attendance->name);
            $collection = collect($names);
            $check_attendance = $user_attendance->attendance()->whereBetween('check_in_time', [Carbon::parse($request->month . '/1')->startOfMonth()->timestamp, Carbon::parse($request->month . '/1')->endOfMonth()->timestamp])->first();
            $get_attendance = $user_attendance->attendance()->whereBetween('check_in_time', [Carbon::parse($request->month . '/1')->startOfMonth()->timestamp, Carbon::parse($request->month . '/1')->endOfMonth()->timestamp])->orderBy('id', 'desc')->get();
            //Check employee marked attendance at least one in a  whole week
            if ($check_attendance != null) {

                foreach ($get_attendance as $attendance) {
                    $total_minutes = 0;
                    //check if employee have check in time in attendance
                    if ($attendance->check_in_time) {
                        if ($attendance->time_spent != 'No Time Spent') {
                            $explode_time_spent = explode(':', $attendance->time_spent);
                            $total_time_spent_minutes = 10 + ($explode_time_spent[0] * 60) + ($explode_time_spent[1]);
                            $total_time_spent_minutes_without_compensation =($explode_time_spent[0] * 60) + ($explode_time_spent[1]);
                            if ($attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, Carbon::parse($attendance->check_out_time)->timestamp)->first() != null) {
                                $get_task = $attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get();
                                foreach ($get_task as $task) {
                                    // check if employee task have time
                                    if ($task->time_take) {

                                        $explode = explode(':', $task->time_take);
                                        $total_minutes += ($explode[0] * 60) + ($explode[1]);

                                    }
                                }
                            }

                            if ($total_time_spent_minutes <= $total_minutes) {
                                $total_logged_minutes = sprintf("%02d:%02d", floor($total_minutes / 60), $total_minutes % 60);
                                $concatinate = $collection->put('logged_time', $total_logged_minutes);
                                $total_spent_minutes = sprintf("%02d:%02d", floor($total_time_spent_minutes_without_compensation / 60), $total_time_spent_minutes_without_compensation % 60);
                                $concatinate = $collection->put('spent_time', $total_spent_minutes);
                                $concatinate = $collection->put('date', $attendance->check_in_time);
                                $user_name[] = array($concatinate->all());
                            }
                        }
                    }
                }

            }




        }
        if (isset($user_name)) {
            $employee_lists = collect($user_name)->sortBy('name');
            return view('Report.inaccuracy',compact('employee_lists'));

        }else{
            return redirect()->route('view.admin.inaccuracy.report')->with('status','No One User have inaccuracy in Task/Attendance');
        }
        }

    public function compensatory()
    {
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        return view('Report.compensatory', compact('users'));
    }

}