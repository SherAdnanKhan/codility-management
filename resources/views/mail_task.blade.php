@component('mail::message')
    # Checkout Report at {{Carbon\Carbon::yesterday()->toDateString()}}

@component('mail::panel')
        Dear Admin ,
        Employee status of today who checkout in last hour.

@endcomponent
@component('mail::table')
    | Employee Name             | CheckIn                                                                                                |  CheckOut                                                                                              |Time                                                                                                                                                                                                                                                                                                                                        |        Break                                                                                  |     Total                                                                                               |Hours Logged                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |   Late/Leave/Absent                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
    | ------------------------- |--------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
    @foreach($get_attendance as $attendance)
        | {{$attendance->user->name}}|{{ $attendance->time_spent != 'No Time Spent'?date('g:i A',strtotime($attendance->check_in_time)):' '}}|{{$attendance->time_spent != 'No Time Spent'?date('g:i A',strtotime($attendance->check_out_time)):' '}} |@php  if($attendance->check_in_time && $attendance->check_out_time){ $checkin=\Carbon\Carbon::parse($attendance->check_in_time);$checkout=\Carbon\Carbon::parse($attendance->check_out_time); $minutes = $checkin->diffInRealMinutes($checkout); $time=\App\Attendance::mktimecustom(date('H:i', mktime(0,$minutes))); echo $time;}@endphp  |{{$attendance->break_interval?\App\Attendance::mktimecustom($attendance->break_interval):' '}} |{{$attendance->time_spent != 'No Time Spent'?\App\Attendance::mktimecustom($attendance->time_spent):' '}}|@php$get_task=$attendance->task_friday(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get(); $day=\Carbon\Carbon::today();@endphp  @foreach ($get_task as $get_time)  @php if ($get_time->time_take){$explode_time = explode(':', $get_time->time_take); $total = ($explode_time[0]*60) + ($explode_time[1]); $combined_time=$day->addRealMinutes($total);}  @endphp @endforeach @php  if(isset($combined_time)){ echo (\App\Attendance::mktimecustom($combined_time->toTimeString())); unset($combined_time); }else{ echo (' ');}@endphp      |{{$attendance->attendance_type=='On Time'?'':$attendance->attendance_type}}  @if($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)){{$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LATE"?'LATE':''}} <p style="color: darkgoldenrod">{{$attendance->late_informed?$attendance->late_informed:''}}</p> @endif @if($attendance->leave_comment){{$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)?$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE"?$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->leaves->name :' ':' '}}@elseif($attendance->leave_comment == null && $attendance->leave_id != null){{$attendance->leave->name}} @endif|
        |
        @php $get_tasks=$attendance->task_friday(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get();@endphp
        @foreach ($get_tasks as $tasks)
        |{{$tasks?'':$attendance->user->name}} <td colspan=6> {{$tasks?$tasks->description:''}} | {{$tasks->time_take?\App\Attendance::mktimecustom($tasks->time_take):''}}          |
@endforeach
    @endforeach
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
