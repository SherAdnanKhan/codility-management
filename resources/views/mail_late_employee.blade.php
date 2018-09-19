@component('mail::message')
# Hey ! <br>

 
The purpose of this email, is to notify you that you are not reached at Office  yet and (an hour been passed). <br>
Please inform the HR Department  Whether you are Late OR on Leave, Otherwise System will mark you as absent for Today. <br>



From HR Department, {{ config('app.name') }}<br>
<br>
Mobile   : 0307-6823026<br>
LandLine : 0423-8937152, 0423-8910394 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
