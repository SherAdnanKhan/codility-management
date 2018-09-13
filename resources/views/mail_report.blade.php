@component('mail::message')
    # Today Report of Employees and their Tasks Generated at : {{Carbon\Carbon::now()->toDayDateTimeString()}}


@component('mail::table')
        | Employee Name             | CheckIn(Date & Time)            |  CheckIn(Date & Time)           |        Break Interval           |     Time Spent             |   Status                       |    Leave                                                                                                                                                                                                                                                      |
        | --------------------------|:--------------------------------|:--------------------------------|:-------------------------------:|:--------------------------:|:------------------------------:|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
        @foreach($report_attendance as $attendance)
        |{{$attendance->user->name}}| {{$attendance->check_in_time}}  | {{$attendance->check_out_time}} | {{$attendance->break_interval}} | {{$attendance->time_spent}}|{{$attendance->attendance_type}}| @if($attendance->leave_comment) {{$attendance->inform->inform_type=='leave'?$attendance->inform->leaves->name :'But Late'}} @elseif($attendance->leave_comment == null && $attendance->leave_id != null) {{$attendance->leave->name}} @else No Concern @endif |
        @endforeach
@endcomponent

@component('mail::table')
        | Name                            | Task                            |Time Tak                         |
        | --------------------------------|:--------------------------------|:--------------------------------|
        @foreach($report_tasks as $task)
        |      {{$task->user->name}}      |{{$task->description}}           | {{$task->time_take}}            |
        @endforeach
        @foreach($report_users as $usersdf)
        | <p style="color: red">{{$usersdf->name}}</p> |<p  style="color: red">Not Done</p>|<p  style="color: red">00:800</p>|
        @endforeach
@endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
