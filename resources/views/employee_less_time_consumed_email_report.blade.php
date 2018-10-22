@component('mail::message')
# Report Date : {{Carbon\Carbon::yesterday()->toDateString()}}

@component('mail::panel')
Dear Admin ,
The Following Employees have less consumed time from there actual time
@endcomponent
@component('mail::table')
    |      Employee  Name |      Total Hours    |      Hours Logged   |      Less Hours |
    | ------------------- | ------------------- | ------------------- | ------------------- |
    @foreach($employee_names as $user)
    |     {{$user['name']}}       |     {{$user['requiredTime']}}       |     {{$user['loggedTime']}}       |     {{$user['lessHours']}}       |
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
