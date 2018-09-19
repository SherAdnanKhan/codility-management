@component('mail::message')
    # Today Report of Employees and their Tasks <br>
    Report Generated at : {{Carbon\Carbon::now()->toDayDateTimeString()}}


@component('mail::table')
        | Employee Name             | CheckIn(Date & Time)            |  CheckIn(Date & Time)           |        Break Interval           |     Time Spent             |Time Consumed                   |   Status                       |    Leave                                                                                                                                                                                                                                                      |
        | --------------------------|:--------------------------------|:--------------------------------|:-------------------------------:|:--------------------------:|:------------------------------:|:------------------------------:|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
        @foreach($report_attendance as $attendance)
        |{{$attendance->user->name}}| {{$attendance->check_in_time}} |{{$attendance->check_out_time}}  | {{$attendance->break_interval}} | {{$attendance->time_spent}}|@php$get_task=$attendance->task_report; $day=\Carbon\Carbon::today();@endphp  @foreach ($get_task as $get_time)  @php $explode_time = explode(':', $get_time->time_take); $total = ($explode_time[0]*60) + ($explode_time[1]); $combined_time=$day->addRealMinutes($total) ;  @endphp @endforeach @php  if(isset($combined_time)){ echo ($combined_time->toTimeString()); }else{ echo ('00:00:00');}@endphp |{{$attendance->attendance_type=='On Time'?'':$attendance->attendance_type}}| @if($attendance->leave_comment) {{$attendance->inform->inform_type=='leave'?$attendance->inform->leaves->name :'But Late'}} @elseif($attendance->leave_comment == null && $attendance->leave_id != null) {{$attendance->leave->name}} @else  @endif |
        @endforeach
@endcomponent


    Thanks,
    {{ config('app.name') }}
@endcomponent
