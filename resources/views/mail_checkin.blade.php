@component('mail::message')
# Employees Attendance Status at {{Carbon\Carbon::now()->toDayDateTimeString()}}

@component('mail::panel')
These Employees are not marked CHECK IN or not present in Office Yet .
@endcomponent
@component('mail::table')
    | Late Employee  Name |
    | ------------------- |
    @foreach($users as $user)
    |     {{$user}}       |
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
