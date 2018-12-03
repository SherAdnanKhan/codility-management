<?php

namespace App\Http\Controllers;

use App\TrackerAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        }elseif (Auth::user()->isEmployee()){
            $day_start=Carbon::now()->startOfDay();
            $day_end=Carbon::now()->endOfDay();
            $user=Auth::user();
            $total_attendance_time=$user->get_tracker_attendance()->whereBetween('date',[$day_start->timestamp,$day_end->timestamp])->whereNotNull('check_out_time')->orderBy('id','desc')->get();
            $tracker_total_time=$user->user_tracker_calculation($day_start->timestamp)->first();
            $tracker_total_status=$user->user_tracker_status()->whereBetween('date',[$day_start->timestamp,$day_end->timestamp])->orderBy('id','desc')->get();
            if (!(emptyArray($tracker_total_status))) {

                $tracker_status_detail = $tracker_total_status->status_tracker_detail()->whereBetween('date', [$day_start->timestamp, $day_end->timestamp])->orderBy('id', 'desc')->get();
            }
            return view('Report.time_tracker');

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
}
