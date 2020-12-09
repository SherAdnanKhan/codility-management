@component('mail::message')
# Hey {{$user['name']}} ! <br>

 
The purpose of this email, is to notify you that you are not reached at Office  yet and (15 minutes grace has been passed). <br>
Please inform the HR Department whether you are late OR on leave, otherwise system will mark you as absent for Today. <br>
<br>
<br>
<br>
<br>
From HR Department, {{ config('app.name') }}<br>
Mobile   : 03044145724<br>
LandLine : 0423-8937152, 0423-8910394 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
