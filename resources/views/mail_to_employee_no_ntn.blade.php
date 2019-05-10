@component('mail::message')
# Dear {{$employee_detail->name}} ! <br>

Kindly make your NTN number as soon as possible and provide it to company. <br>
Thank you !
<br>
<br>
<br>
<br>
From HR Department, {{ config('app.name') }}<br>
Mobile   : 0307-6823026,03064188742<br>
LandLine : 0423-8937152, 0423-8910394 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
