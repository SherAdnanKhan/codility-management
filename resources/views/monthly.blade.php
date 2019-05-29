@component('mail::message')
# Report Date : @php $current_month=\Carbon\Carbon::now()->month;
        $previous_month=\Carbon\Carbon::now()->subDays(6)->month;
        if($current_month != $previous_month){
            $start_date_carbon=\Carbon\Carbon::parse($previous_month . '/1');
            $end_date_carbons=\Carbon\Carbon::parse($previous_month . '/1');
        }else{
            $start_date_carbon=\Carbon\Carbon::now();
            $end_date_carbon=\Carbon\Carbon::now();
        }
        if (!(isset($end_date_carbons))) {
            $date=\Carbon\Carbon::now()->endOfWeek()->subDays(1)->subHours(16)->format('d-m-Y');
        }else{
            $date=$end_date_carbons->endOfMonth()->format('d-m-Y');
        }@endphp
{{$start_date_carbon->startOfMonth()->format('d-m-Y')}} To {{$date}}

@component('mail::panel')
The Following are the Employee list
@endcomponent
@component('mail::table')
    |      Employee  Name |      Total Hours                                       |        Logged                                        |         Less                                        |       Informed late      | UnInformed Late  | Informed Leaves   | UnInformed Leaves  |
    | ------------------- | ------------------------------------------------------ | ---------------------------------------------------- | --------------------------------------------------- |--------------------------|----------------- |------------------ |------------------- |
    @foreach($employee_names as $item)
        @foreach($item as $user)
            | <p class="heading_">{{$user['name']}} </p>  |{{\App\Attendance::mktimesimple($user['requiredWithoutCompansetionTime'])}}|{{\App\Attendance::mktimesimple($user['loggedTime'])}}|{{\App\Attendance::mktimesimple($user['lessTimeWithoutCompensation'])}}|{{$user['informed_late']}}|{{$user['late']}} |{{$user['leave']}} |{{$user['absent']}} |
        @endforeach
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
