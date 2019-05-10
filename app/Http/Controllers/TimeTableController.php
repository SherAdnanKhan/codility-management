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
        $morning_timetable = TimeTable::first();
        $evening_timetable = TimeTable::whereId(2)->first();

        return view('Admin.timetable',compact('morning_timetable','evening_timetable'));
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
        $non_working_hour = Carbon::parse($request->non_working_hour)->timestamp;
        $time = TimeTable::whereId(1)->first();
        $get_start_time = Carbon::parse($time->start_time )->timestamp;
        $get_end_time   =Carbon::parse($time->end_time )->timestamp;
        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();
        $check_start_time=Carbon::parse($time->start_time)->format('H:i');
        $check_end_time=Carbon::parse($time->end_time)->format('H:i');
        $time_table= TimeTable::whereId(1)->first();
        $time_table->update([
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
        $check_day['monday']        = $time_table->monday;
        $check_day['tuesday']       = $time_table->tuesday;
        $check_day['wednesday']     = $time_table->wednesday;
        $check_day['thursday']      = $time_table->thursday;
        $check_day['friday']        = $time_table->friday;
        $check_day['saturday']      = $time_table->saturday;
        $check_day['sunday']        = $time_table->sunday;
        $all_days                   = count(array_keys($check_day, 7));

        foreach ($users as $user){

            if ($check_start_time == Carbon::parse($user->checkInTime)->format('H:i') && $check_end_time == Carbon::parse($user->checkOutTime)->format('H:i')){

                $user->update([
                    'checkInTime'           => Carbon::parse($request->start_time )->timestamp,
                    'checkOutTime'          => Carbon::parse($request->end_time )->timestamp,
                    'breakAllowed'          => Carbon::parse($request->non_working_hour)->timestamp,
                    'workingDays'           => $all_days,
                    'shift_time'            => $time_table->id

                ]);
            }
        }


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

    public function evening_shift(Request $request)
    {
        $this->validate($request, [
            'evening_start_time' => 'required',
            'evening_end_time' => 'required',
            'evening_working_hour' => 'required',
            'evening_non_working_hour' => 'required'

        ]);

        $start_time = Carbon::parse($request->evening_start_time )->timestamp;
        $end_time = Carbon::parse($request->evening_end_time )->timestamp;
        $working_hour = Carbon::parse($request->evening_working_hour )->timestamp;
        $non_working_hour = Carbon::parse($request->evening_non_working_hour)->timestamp;
        $time = TimeTable::whereId(1)->first();
        $get_start_time = Carbon::parse($time->start_time )->timestamp;
        $get_end_time   =Carbon::parse($time->end_time )->timestamp;
        $users =User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();
        $check_start_time=Carbon::parse($time->start_time)->format('H:i');
        $check_end_time=Carbon::parse($time->end_time)->format('H:i');
        $time_table= TimeTable::whereId(2)->first();
        $time_table->update([
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
        $check_day['monday']        = $time_table->monday;
        $check_day['tuesday']       = $time_table->tuesday;
        $check_day['wednesday']     = $time_table->wednesday;
        $check_day['thursday']      = $time_table->thursday;
        $check_day['friday']        = $time_table->friday;
        $check_day['saturday']      = $time_table->saturday;
        $check_day['sunday']        = $time_table->sunday;
        $all_days                   = count(array_keys($check_day, 7));

        foreach ($users as $user){
            if ($check_start_time == Carbon::parse($user->checkInTime)->format('H:i') && $check_end_time == Carbon::parse($user->checkOutTime)->format('H:i')){
                $user->update([
                    'checkInTime'           => Carbon::parse($request->evening_start_time)->timestamp,
                    'checkOutTime'          => Carbon::parse($request->evening_end_time)->timestamp,
                    'breakAllowed'          => Carbon::parse($request->evening_non_working_hour)->timestamp,
                    'workingDays'           => $all_days,
                    'shift_time'            => $time_table->id
                ]);
            }
        }


        return redirect('/timetable')->with('timetable','TimeTable Changed SuccessFull');
    }
}
