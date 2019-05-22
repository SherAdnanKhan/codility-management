@component('mail::message')
# Dear {{$employee_detail->name}} ! <br>

Kindly make your NTN number as soon as possible and provide it to company. <br>
Thank you !
<br>
<br>
<br>
<br>
From HR Department, {{ config('app.name') }}<br>
Mobile   : 0304-4145724<br>
LandLine : 0423-5222747 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
