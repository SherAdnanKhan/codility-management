@component('mail::message')
# Report Date : {{Carbon\Carbon::now()->startOfWeek()->format('d-m-Y')}} To {{Carbon\Carbon::now()->startOfWeek()->addDays(4)->format('d-m-Y')}}

@component('mail::panel')
The Following are the Employee list
@endcomponent
@component('mail::table')
    |      Employee  Name |      Total Hours    |      Hours Logged   |      Less Hours |
    | ------------------- | ------------------- | ------------------- | ------------------- |
    @foreach($employee_names as $user)
    |     {{$user['name']}}       |     {{\App\Attendance::mktimesimple($user['requiredTime'])}}       |     {{\App\Attendance::mktimesimple($user['loggedTime'])}}       |     {{\App\Attendance::mktimesimple($user['lessHours'])}}       |
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
