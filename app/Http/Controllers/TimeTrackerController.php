<?php

namespace App\Http\Controllers;

use App\TrackerAttendance;
use App\TrackerStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TimeTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isAdmin()){
            $date_coming=Carbon::now()->startOfDay()->timestamp;
            $users=User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->get();
            return view('Report.admin_time_tracker',compact('users','date_coming'));

        }elseif (Auth::user()->isEmployee()){
            $date=Carbon::now()->startOfDay();
            $user = User::whereId(Auth::user()->id)->first();
            $status_check = $user->user_tracker_status()->where('date', $date->timestamp)->first();
            $status = $user->user_tracker_status()->where('date', $date->timestamp)->get();
            $calculation = $user->user_tracker_calculation($date->timestamp)->first();
            $tasks = $user->user_tracker_task()->where('date', $date->timestamp)->get();
            $date_coming = $date->timestamp;
            $user_id = $user->id;
            if (($status_check== null) && ($calculation == null)) {
//                Session::
                return view('Report.time_tracker', compact('users', 'date_coming'))->with('status_error','No Record Found !');
            }
            return view('Report.time_tracker', compact('status', 'calculation', 'tasks', 'date_coming', 'user_id'));

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
    public function makeTimeTrackReport(Request $request){
        if (Auth::user()->isAdmin()) {
            $this->validate($request, [
                'employee' => 'required',
                'date' => 'required|date',
                'check' => 'required',

            ]);
            if ($request->check == 'previous') {
                $date = Carbon::parse($request->date)->subDay(1)->startOfDay();
            }
            if ($request->check == 'next') {
                $date = Carbon::parse($request->date)->addDay(1)->startOfDay();
            }

            $user = User::where('id', $request->employee)->first();
            $status = $user->user_tracker_status()->where('date', $date->timestamp)->get();
            $status_check = $user->user_tracker_status()->where('date', $date->timestamp)->first();
            $calculation = $user->user_tracker_calculation($date->timestamp)->first();
            $users = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->get();
            $tasks = $user->user_tracker_task()->where('date', $date->timestamp)->get();
            $date_coming = $date->timestamp;
            $user_id = $user->id;
            if (($status_check == null) && ($calculation == null)) {
                return view('Report.admin_time_tracker', compact('users', 'date_coming','user_id'))->with('status_error', 'Not Found Record');
            }
            return view('Report.admin_time_tracker', compact('status', 'calculation', 'users', 'tasks', 'date_coming', 'user_id','user'));
        }
        if (Auth::user()->isEmployee()){
            $this->validate($request, [
                'date' => 'required|date',
            ]);

            if ($request->check == 'previous') {
                $date = Carbon::parse($request->date)->subDay(1)->startOfDay();
            }
            if ($request->check == 'next') {
                $date = Carbon::parse($request->date)->addDay(1)->startOfDay();
            }
            $user = User::where('id', Auth::user()->id)->first();
            $status_check = $user->user_tracker_status()->where('date', $date->timestamp)->first();
            $status = $user->user_tracker_status()->where('date', $date->timestamp)->get();
            $calculation = $user->user_tracker_calculation($date->timestamp)->first();
            $tasks = $user->user_tracker_task()->where('date', $date->timestamp)->get();
            $date_coming = $date->timestamp;
            $user_id = $user->id;
            if (($status_check == null) && ($calculation == null)) {
                return view('Report.time_tracker', compact('users', 'date_coming'))->with('status_error','No Record Found !');
            }
            return view('Report.time_tracker', compact('status', 'calculation', 'users', 'tasks', 'date_coming', 'user_id'));

        }
    }
}
