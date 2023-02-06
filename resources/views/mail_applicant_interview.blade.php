@component('mail::message')
    # Dear {{$applicant_detail['name']}},<br>

@component('mail::panel')

        {!! isset($content)?$content:''!!}
@endcomponent
From HR Department, <br>{{ config('app.name') }}<br>

Mobile   : 0307-6823026<br>
LandLine : 0423-8937152, 0423-8910394 <br>
<p>Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
