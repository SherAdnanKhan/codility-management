<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Inform;
use App\Leave;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves =Leave::all()->sortByDesc('id');
        return view('Admin.leave',compact('leaves'));
        
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
        $this->validate($request,[
            'name'      =>'required',
            'color_code'=>'required',
            'allowed'   =>'required',
//            'date'      =>'required_if:public_holiday,on'

        ]);
        $public_holiday=$request->public_holiday== null ?false:true;

        if ($public_holiday == true && $request->date){
            $date=Carbon::parse($request->date);
            $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']);})->where('abended',false)->get();
            $leave = Leave::create([
                'name'  =>  $request->name,
                'color_code'  => $request->color_code,
                'allowed'  => $request->allowed,
                'public_holiday'  => $public_holiday,
                'date'            =>$date->timestamp

            ]);
            foreach ($users as $user){

                $inform= Inform::create([
                    'attendance_date'=>$date->timestamp,
                    'inform_at'      =>Carbon::now()->timestamp,
                    'user_id'        =>$user->id,
                    'inform_type'    =>'leave',
                    'inform_late'    =>false,
                    'leave_type'     =>$leave->id,
                    'reason'         =>$leave->name,
                ]);
            }

        }else {
            $leave = Leave::create([
                'name' => $request->name,
                'color_code' => $request->color_code,
                'allowed' => $request->allowed,
//                'public_holiday' => $public_holiday,

            ]);
        }
        return redirect()->route('leave.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit($id)
    {

        $leave = Leave::findOrFail($id);
        $data= view('layouts.modal-view',compact('leave'))->render();
        return \response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $leave = Leave::whereId($id)->first();
        $public_holiday=$request->public_holiday== null ?false:true;

        if ($public_holiday == true && $request->date) {

            $dateofholiday = Carbon::parse($request->date);
            $leave_date = $leave->date ? Carbon::createFromTimestamp($leave->date) : null;
            if (($leave_date->startOfYear()->eq(Carbon::parse($request->date)->startOfYear()))) {
                if ((Carbon::createFromTimestamp($leave->date)->lessThan(Carbon::now()->startOfDay()))) {
                    return redirect('/leave')->with('status_error', 'This leave have passed and Employees on this leave that,s why you did not change date now. You can change in next year');

                }

            }

            if (Carbon::createFromTimestamp($leave->date)->gt(Carbon::now())) {

//                if ((Carbon::createFromTimestamp($leave->date)->gt(Carbon::parse($request->date)))) {
                    $date = Carbon::parse($request->date);
                    $users = User::whereHas('role', function ($q) {
                        $q->whereIn('name', ['Employee']);
                    })->where('abended', false)->get();

                    $informs = Inform::whereBetween('attendance_date', [Carbon::createFromTimestamp($leave->date)->startOfDay()->timestamp, Carbon::createFromTimestamp($leave->date)->endOfDay()->timestamp])->get();

                    foreach ($informs as $inform) {

                        $inform->attendance_date = Carbon::parse($request->date)->timestamp;
                        $inform->update();

                    }
                    $leave->date=Carbon::parse($request->date)->timestamp;
                    $leave->name  =$request->name;
                    $leave->color_code  =$request->color_code;
                    $leave->allowed  =$request->allowed;
                    $leave->save();
//                }
            } else {

                return redirect('/leave')->with('status_error', 'This leave have passed and employees on this leave thats why you did not change date now ');
            }

                }else {
                $leave->update([
                    'name'  =>  $request->name,
                    'color_code'  => $request->color_code,
                    'allowed'  => $request->allowed,

                ]);
                }

        return redirect()->route('leave.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leave = Leave::whereId($id)->delete();
        return redirect()->route('leave.index');

    }
    public function leave(){
        $result = Leave::all();
        return \response()->json($result);
    }
}
