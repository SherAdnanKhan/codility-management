@component('mail::message')
    # Employees Today Task Status {{Carbon\Carbon::now()->toDayDateTimeString()}}

@component('mail::panel')
        Dear Admin ,
        These Task performed by Employee Today who checkout last Hour.

@endcomponent
@component('mail::table')
    | Name                            | Task                            |Time Tak                         |
    | --------------------------------|:--------------------------------|:--------------------------------|
        @foreach($get_tasks as $extract_task)
            @foreach($extract_task as $task)
    |      {{$task->user->name}}      |{{$task->description}}           | {{$task->time_take}}            |
             @endforeach
        @endforeach
    @foreach($users as $user)
        | <p style="color: red">{{$user->name}}</p> |<p  style="color: red">Not Done</p>|<p  style="color: red">00:800</p>|
    @endforeach
@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
