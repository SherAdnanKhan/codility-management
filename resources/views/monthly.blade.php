@component('mail::message')
# Report Date : {{Carbon\Carbon::now()->startOfMonth()->format('d-m-Y')}} To {{Carbon\Carbon::now()->endOfWeek()->subDays(1)->subHours(16)->format('d-m-Y')}}

@component('mail::panel')
The Following are the Employee list
@endcomponent
@component('mail::table')
    |      Employee  Name |      Total Hours                                       |        Logged                                        |         Less                                        |       Informed late      | UnInformed Late  | Informed Leaves   | UnInformed Leaves  |
    | ------------------- | ------------------------------------------------------ | ---------------------------------------------------- | --------------------------------------------------- |--------------------------|----------------- |------------------ |------------------- |
    @foreach($employee_names as $item)
        @foreach($item as $user)
    | {{$user['name']}}   |{{\App\Attendance::mktimesimple($user['requiredWithoutCompansetionTime'])}}|{{\App\Attendance::mktimesimple($user['loggedTime'])}}|{{\App\Attendance::mktimesimple($user['lessHours'])}}|{{$user['informed_late']}}|{{$user['late']}} |{{$user['leave']}} |{{$user['absent']}} |
        @endforeach
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
