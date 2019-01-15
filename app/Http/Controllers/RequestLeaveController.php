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
            $request_leaves=RequestLeave::where('user_id',Auth::user()->id)->orderBy('from_date','desc')->paginate(10);

            return view('Employee.request_leave',compact('request_leaves'))->with('status','In case of Employee need leave(s), Employee should get an approval from Administration. Only sick leave is allowed on same day or in case of emergency !');
        }elseif (Auth::user()->isAdmin()){
            $request_leaves=RequestLeave::where('from_date','>=',Carbon::now()->startOfDay()->timestamp)->orderBy('from_date','desc')->paginate(10);
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
//            'leave' => 'required',
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

        $send_request_mail=Mail::send(new LeaveRequest($leave_request));
        return redirect()->route('request.index')->with('status','Your request for getting approval is submit .After reviewing your approval you will get an email about  ');

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
        $leaves=$request_leave->leave_id;
        if ($leaves != null){
            $selected_leave=$leaves;

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
        $leave = Leave::whereId($request->leave)->first();
        $request_leave = RequestLeave::whereId($id)->first();
        if($request->status == 'declined'){
            $request_leave->approved = '2';
            $request_leave->leave_id = $request->leave?$request->leave:null;
            $request_leave->save();
        }
            if ($request->status == 'approved') {
                $request_leave->approved = true;
                $request_leave->leave_id = $request->leave?$request->leave:null;
                $request_leave->save();
            } elseif ($request->status == 'not_approved') {
                $request_leave->approved = false;
                $request_leave->leave_id = $request->leave?$request->leave:null;
                $request_leave->save();
            }

        $request_leaves = RequestLeave::whereId($id)->first();
        $send_request_mail=Mail::send(new EmployeeRequestApproved($request_leaves));

        return redirect()->route('request.index')->with('status','Leave Request ');
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
        $name=$request->name?$request->name:null;
        if ($name != null) {
            $user = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->where('name', 'Like', '%' . $name . '%')->first();
        }
        if ($request->filter == null){

            $name=$request->name?$request->name:null;
            $user = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->where('name','Like','%'.$name.'%')->pluck('id');

            $request_leaves = RequestLeave::whereIn('user_id', [$user])->paginate(10);
            $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
//            return view('Employee.admin_request_leave',compact('request_leaves'));
        }

        if ($request->filter == 'approved'){

            if ($name != null){
                $request_leaves = RequestLeave::where(['approved'=>true, 'user_id'=> $user != null ? $user->id : ''])->orderBy('from_date','desc')->paginate(10);
                $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
                return view('Employee.admin_request_leave',compact('request_leaves'));
            }else{
                $request_leaves = RequestLeave::where('approved',true)->orderBy('from_date','desc')->paginate(10);

                $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
                return view('Employee.admin_request_leave',compact('request_leaves'));
            }
        }
        if ($request->filter == 'declined'){

            if ($name != null){
                $request_leaves = RequestLeave::where(['approved'=>'2', 'user_id'=> $user != null ? $user->id : ''])->orderBy('from_date','desc')->paginate(10);

                $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
                return view('Employee.admin_request_leave',compact('request_leaves'));
            }else{
                $request_leaves = RequestLeave::where('approved',2)->orderBy('from_date','desc')->paginate(10);
                $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
                return view('Employee.admin_request_leave',compact('request_leaves'));
            }
        }
        if ($request->filter =='not_approved'){

            if ($name != null){

                $request_leaves = RequestLeave::where(['approved'=>false, 'user_id'=>  $user != null ? $user->id : ''])->orderBy('from_date','desc')->paginate(10);
                $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
                return view('Employee.admin_request_leave',compact('request_leaves'));
            }else{
                $request_leaves = RequestLeave::where('approved',false)->orderBy('from_date','desc')->paginate();
                $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
                return view('Employee.admin_request_leave',compact('request_leaves'));
            }

        }
        if($request->filter == 'custom') {
            $this->start_date = Carbon::parse($request->start_date)->timestamp;
            $this->end_date   = Carbon::parse($request->end_date)->timestamp;
        }else{

            $start_date = Carbon::now();
            $this->start_date=$request->filter=='today'?$start_date->startOfDay()->timestamp:($request->filter == 'week'?$start_date->startOfWeek()->timestamp:($request->filter == 'month'?$start_date->startOfMonth()->timestamp:($request->filter =='year'?$start_date->startOfYear()->timestamp:'')));
            $this->end_date=$request->filter=='today'?$start_date->endOfDay()->timestamp:($request->filter == 'week'?$start_date->endOfWeek()->timestamp:($request->filter == 'month'?$start_date->endOfMonth()->timestamp:($request->filter =='year'?$start_date->endOfYear()->timestamp:'')));
        }


        if ($name != null) {

            $request_leaves = RequestLeave::whereBetween('from_date', [$this->start_date, $this->end_date])->where('user_id', $user != null ? $user->id : '')->orderBy('from_date','desc')->paginate(10);

        }else{

            $request_leaves = RequestLeave::whereBetween('from_date', [$this->start_date, $this->end_date])->orderBy('from_date','desc')->paginate(10);

        }

        $request_leaves->withPath("?filter=$request->filter&start_date=$request->start_date&end_date=$request->end_date&name=$name");
        return view('Employee.admin_request_leave',compact('request_leaves'));

    }
}
