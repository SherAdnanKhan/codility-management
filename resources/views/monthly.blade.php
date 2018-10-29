@component('mail::message')
# Report Date : {{Carbon\Carbon::now()->startOfWeek()->format('d-m-Y')}} To {{Carbon\Carbon::now()->startOfWeek()->addDays(4)->format('d-m-Y')}}

@component('mail::panel')
The Following are the Employee list
@endcomponent
@component('mail::table')
    |      Employee  Name |      Total Hours    |      Hours Logged   |      Less Hours     |      Total UnInformed Late     |      Total Informed Late     |      Total Leaves    |      Total Absent     |
    | ------------------- | ------------------- | ------------------- | ------------------- |------------------------------- |----------------------------- |--------------------- |---------------------- |
    @foreach($employee_names as $item)
        @foreach($item as $user)
            |     {{$user['name']}}      |     {{\App\Attendance::mktimesimple($user['requiredTime'])}}       |     {{\App\Attendance::mktimesimple($user['loggedTime'])}}       |     {{\App\Attendance::mktimesimple($user['lessHours'])}}  |{{$user['late']}} |{{$user['informed_late']}} |{{$user['leave']}} |{{$user['absent']}} |
        @endforeach
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
