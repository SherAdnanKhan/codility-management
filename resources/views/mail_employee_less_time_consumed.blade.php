@component('mail::message')
# Weekly Assessment Report

@component('mail::panel')
Dear Employee ,
Weekly evaluation reports have revealed to us that your performance is not up to the mark and satisfactory. <br>
The company intends to warn you through this letter, that such consistent this performance would not be tolerated for a long time.<br>
Feel free to talk out any problems from your side.
@endcomponent
From HR Department, {{ config('app.name') }}<br>
<br>
Mobile   : 0307-6823026<br>
LandLine : 0423-8937152, 0423-8910394 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
