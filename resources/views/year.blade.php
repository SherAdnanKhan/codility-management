@component('mail::message')
# Yearly Report Till : {{Carbon\Carbon::now()->endOfMonth()->format('d-m-Y')}}

@component('mail::panel')
The Following are the Employee list
@endcomponent
@component('mail::table')
    |      Employee  Name |      Total Absents  |     Current Month Absents  |     Allowed Absents |
    | ------------------- | ------------------- | -------------------------- | ------------------- |
    @foreach($user_details as $item)
        @foreach($item as $user)
            |     {{$user['name']}}      |     {{$user['total_absent']}}       |     {{$user['current_month_absent']}}       |     {{$user['allowed_absent']}}  |
        @endforeach
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
