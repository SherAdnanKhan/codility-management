<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\TimeTable;
class TimeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timetable = TimeTable::all();
        return view('Admin.timetable',compact('timetable'));
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
            'start_time' => 'required',
            'end_time' => 'required',
            'working_hour' => 'required',
            'non_working_hour' => 'required'

        ]);

        $start_time = Carbon::parse($request->start_time )->timestamp;
        $end_time = Carbon::parse($request->end_time )->timestamp;
        $working_hour = Carbon::parse($request->working_hour )->timestamp;
        $non_working_hour = Carbon::parse($request->non_working_hour )->timestamp;
        $time = TimeTable::whereId(1)->first();
        $get_start_time = Carbon::parse($time->start_time )->timestamp;
        $get_end_time   =Carbon::parse($time->end_time )->timestamp;
        $user =User::where(['checkInTime'=>$get_start_time,'checkOutTime'=>$get_end_time])->update([
            'checkInTime' => $start_time,
            'checkOutTime' => $end_time,
        ]);
        $time_table= TimeTable::whereId(1)->update([
            'start_time'         =>$start_time,
            'end_time'           =>$end_time,
            'working_hour'       =>$working_hour,
            'non_working_hour'   =>$non_working_hour,
            'monday'             =>$request->monday?true:false,
            'tuesday'            =>$request->tuesday?true:false,
            'wednesday'          =>$request->wednesday?true:false,
            'thursday'           =>$request->thursday?true:false,
            'friday'             =>$request->friday?true:false,
            'saturday'           =>$request->saturday?true:false,
            'sunday'             =>$request->sunday?true:false,
        ]);

        return redirect('/timetable')->with('timetable','TimeTable Changed SuccessFull');
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


}
