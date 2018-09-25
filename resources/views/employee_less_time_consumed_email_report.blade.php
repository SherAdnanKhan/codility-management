@component('mail::message')
# Report Generated at : {{Carbon\Carbon::now()->toDayDateTimeString()}}

@component('mail::panel')
Dear Admin ,
The Following Employees have less consumed time from there actual time
@endcomponent
@component('mail::table')
    |      Employee  Name |
    | ------------------- |
    @foreach($employee_names as $user)
    |     {{$user}}       |
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
