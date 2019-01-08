<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Inform;
use App\Leave;
use App\Mail\EmployeeRequestApproved;
use App\Mail\LeaveRequest;
use App\RequestLeave;
use App\User;
use Carbon\Carbon;
use Cron\AbstractField;
use function GuzzleHttp\Promise\is_rejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;

class RequestLeaveController extends Controller
{
    public $start_date;
    public $end_date;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isEmployee()){
                return view('Employee.request_leave')->with('status','In case of  Employee needs UPTO TWO consecutive Absent ,Employee should hEmployee should compulsory to get an approval from Administration. In case of one also. Only sick leave is allowed on same day or in case of emergency ');
        }elseif (Auth::user()->isAdmin()){
            $request_leaves=RequestLeave::orderBy('id','desc')->paginate(10);
            return view('Employee.admin_request_leave',compact('request_leaves'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'from_date' => 'required|date',
            'to_date' => 'required_if:start_date,date',
            'leave' => 'required',
            'reason'=>'required'
        ]);
        $user=Auth::user();
        $leave = Leave::whereId($request->leave)->first();

        $request_to_date = Carbon::parse($request->to_date);


        if ($request->to_date) {
            $to_date = Carbon::parse($request->to_date)->timestamp;
            $from_date = Carbon::parse($request->from_date)->timestamp;
        }else{
            $to_date = null;
            $from_date = Carbon::parse($request->from_date)->timestamp;
        }

        $leave_request=RequestLeave::create([
            'to_date'       => $to_date == null? null:$to_date,
            'from_date'     => $from_date,
            'reason'        => $request->reason,
            'approved'      => false,
            'user_id'       => Auth::id()

        ]);

        if ($leave_request->to_date != null ) {

            $request_from_date = Carbon::createFromTimestamp($leave_request->from_date);
//        $get_diff=$this->generateDateRange($request_from_date,$request_to_date);



            if ($user->workingDays == 5) {

                $get_leave_dates = $user->generateDateRange($request_from_date, $request_to_date);

            } elseif ($user->workingDays == 6) {
                $get_leave_dates = $user->generateDateRangeWithSunday($request_from_date, $request_to_date);

            }

            if (isset($get_leave_dates)) {

                foreach ($get_leave_dates as $get_leave_date) {

                    $check_attendance = Attendance::whereBetween('check_in_time', [Carbon::parse($get_leave_date)->startOfDay()->timestamp, Carbon::parse($get_leave_date)->endOfDay()->timestamp])->where('user_id', $user->id)->first();
                    $check_informs=Inform::whereBetween('attendance_date', [Carbon::parse($get_leave_date)->startOfDay()->timestamp, Carbon::parse($get_leave_date)->endOfDay()->timestamp])->where('user_id', $user->id)->first();
                    if ($check_informs != null){
                        return view('Employee.request_leave')->with('status',"You Already have submitted request of this date $get_leave_date");
                    }
                    $inform = Inform::create([
                        'attendance_date' => Carbon::parse($get_leave_date)->startOfDay()->timestamp,
                        'inform_at' => Carbon::parse($leave_request->created_at)->timestamp,
                        'user_id' => $user->id,
                        'inform_type' => 'leave',
                        'reason' => $leave_request->reason,
                        'inform_late' => false,
                        'leave_type' => $leave->id,
                        'request_id'   => $leave_request->id

                    ]);


                }

            }

        }elseif ($leave_request->to_date == null ){


            $check_attendance = Attendance::whereBetween('check_in_time', [Carbon::parse($request->to_date)->startOfDay()->timestamp, Carbon::parse($request->to_date)->endOfDay()->timestamp])->where('user_id', $user->id)->first();

            $inform = Inform::create([
                'attendance_date' => Carbon::parse($leave_request->to_date)->startOfDay()->timestamp,
                'inform_at' => Carbon::parse($leave_request->created_at)->timestamp,
                'user_id' => $user->id,
                'inform_type' => 'leave',
                'reason' => $leave_request->reason,
                'inform_late' => false,
                'leave_type' => $leave->id,
                'request_id'   => $leave_request->id

            ]);
        }
        $send_request_mail=Mail::send(new LeaveRequest($leave_request));
        return view('Employee.request_leave')->with('status','Your request for getting approval is submit .After reviewing your approval you will get an email about  ');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request_leave= RequestLeave::whereId($id)->first();
        $informs=$request_leave->get_inform_request->first();
        if ($informs != null){
            $selected_leave=$informs->leave_type;

        }else{
            $selected_leave=null;
        }
        $data= view('layouts.modal-view',compact('request_leave','selected_leave'))->render();
        return \response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'leave' => 'required',


        ]);
        $leave = Leave::whereId($request->leave)->first();
        $request_leave = RequestLeave::whereId($id)->first();
        if ($request->approved == 'on'){
            $request_leave->approved = true;
            $request_leave->save();
        }elseif($request->approved == null){
            $request_leave->approved = false;
            $request_leave->save();
        }

        if ($request_leave->get_inform_request){
            $all_informs=$request_leave->get_inform_request()->get();
            foreach ($all_informs as $inform) {
                $inform->leave_type=$leave->id;
                $inform->save();
            }
        }

        $send_request_mail=Mail::send(new EmployeeRequestApproved($request_leave));

        return redirect()->route('request.index')->with('status','Leave Request APPROVED');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function search(Request $request){

        $this->validate($request,[
            'start_date' =>'required_if:filter,custom',
            'end_date'  =>'required_if:filter,custom',
        ]);
        if ($request->filter == null){
            $name=$request->name?$request->name:null;
            $user = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->where('name','Like','%'.$name.'%')->pluck('id');

            $request_leaves = RequestLeave::whereIn('user_id', [$user])->paginate(10);
            $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
            return view('Employee.admin_request_leave',compact('request_leaves'));
        }
        if($request->filter == 'custom') {
            $this->start_date = Carbon::parse($request->start_date)->timestamp;
            $this->end_date   = Carbon::parse($request->end_date)->timestamp;
        }else{

            $start_date = Carbon::now();
            $this->start_date=$request->filter=='today'?$start_date->startOfDay()->timestamp:($request->filter == 'week'?$start_date->startOfWeek()->timestamp:($request->filter == 'month'?$start_date->startOfMonth()->timestamp:($request->filter =='year'?$start_date->startOfYear()->timestamp:'')));
            $this->end_date=$request->filter=='today'?$start_date->endOfDay()->timestamp:($request->filter == 'week'?$start_date->endOfWeek()->timestamp:($request->filter == 'month'?$start_date->endOfMonth()->timestamp:($request->filter =='year'?$start_date->endOfYear()->timestamp:'')));
        }
        $name=$request->name?$request->name:null;
        if ($name != null){
        $user = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('name','Like','%'.$name.'%')->first();
        }
        if ($name != null) {

            $request_leaves = RequestLeave::whereBetween('from_date', [$this->start_date, $this->end_date])->where('user_id', $user != null ? $user->id : '')->paginate(10);

        }else{

            $request_leaves = RequestLeave::whereBetween('from_date', [$this->start_date, $this->end_date])->paginate(10);

        }

        $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
        return view('Employee.admin_request_leave',compact('request_leaves'));

    }
}
