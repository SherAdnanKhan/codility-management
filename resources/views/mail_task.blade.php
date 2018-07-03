@component('mail::message')
    # Employees Today Task Status {{Carbon\Carbon::now()->toDayDateTimeString()}}

@component('mail::panel')
        Dear Admin ,
        These Task performed by Employee Today who checkout last Hour.
        @foreach($users as $user)
            {{'except  '.$user->name}}
        @endforeach
@endcomponent
@component('mail::table')
    | Name                            | Task                            |Time Tak                         |
    | --------------------------------|:--------------------------------|:--------------------------------|
        @foreach($get_tasks as $extract_task)
            @foreach($extract_task as $task)
    |      {{$task->user->name}}      |{{$task->description}}           | {{$task->time_take}}            |
             @endforeach
        @endforeach
@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
