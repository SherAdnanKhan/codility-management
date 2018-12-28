@component('mail::message')
# Monthly Assessment Report

@component('mail::panel')
Dear {{$user_detail['name']}},<br>
Your monthly attendance report has revealed that you are working desired hours written and agreed in company's contract <br>
without any prior mentioned reason. Please make sure to cover remaining hours during this month. Also,
Please make sure let your immediate manager know how you will cover short hours during this month to be on same page.<br>
Please Note that short hours could effect your annual evaluation report.<br>
@endcomponent

    @php $get_attendance=\App\Attendance::whereBetween('check_in_time',[\Carbon\Carbon::now()->startOfMonth()->timestamp,\Carbon\Carbon::now()->timestamp])->where('user_id',$user_detail['user_id'])->orderBy('check_in_time','desc')->get();@endphp

@component('mail::table')
    | CheckIn                                                                       |  CheckOut                                                                     |        Break                                                                                  |     Total Time Spent                                                                                    |   Late/Leave/Absent                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
    | ----------------------------------------------------------------------------- | ----------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
    @foreach ($get_attendance as $attendance)
     |{{ $attendance->check_in_time}}|{{$attendance->check_out_time}} ||{{$attendance->break_interval?\App\Attendance::mktimecustom($attendance->break_interval):' '}} |{{$attendance->time_spent != 'No Time Spent'?\App\Attendance::mktimecustom($attendance->time_spent):' '}}|{{$attendance->attendance_type=='On Time'?'':$attendance->attendance_type}}  @if($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)) {{$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LATE"?'LATE':''}} <p style="color: darkgoldenrod">{{$attendance->late_informed?$attendance->late_informed:''}}</p> @endif @if($attendance->leave_comment){{$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)?$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE"?($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->request_id != null ?$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get_request_leave->approved:'').$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->leaves->name :' ':' '}}@elseif($attendance->leave_comment == null && $attendance->leave_id != null){{$attendance->leave->name}} @endif|
     @endforeach
@endcomponent

## Summary
@component('mail::table')
    |      Total Hours                 |       Hours Logged             |      Less Hours                   |
    | -------------------------------- | ------------------------------ | --------------------------------- |
    | {{$user_detail['requiredWithoutCompansetionTime']}} | {{$user_detail['loggedTime']}} |     {{$user_detail['lessHours']}} |
@endcomponent
From HR Department, <br>{{ config('app.name') }}<br>

Mobile   : 0307-6823026<br>
LandLine : 0423-8937152, 0423-8910394 <br>
<p>Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
