@component('mail::message')
# Hey {{isset($user)?$user->name:'Employee'}} ! <br>

 
The purpose of this email, is to notify you that your request for leaves is reviewed by HR and your request is {{strtolower($get_leave_request->approved)}}

{{$get_leave_request->approved == 'Declined' ? 'Concern to your HR Department/Send request again':''}}

<br>
<br>
<br>
<br>
From HR Department, {{ config('app.name') }}<br>
Mobile   : 03044145724<br>
LandLine : 0423-8937152, 0423-8910394 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
