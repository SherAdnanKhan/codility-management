@component('mail::message')
# Hey {{isset($user)?$user->name:'Employee'}} ! <br>

 
The purpose of this email, is to notify you that your Request for Leaves is reviewed by HR and your request is {{$get_leave_request->approved}}

{{$get_leave_request->approved == 'Declined' ? 'Concern to your HR Department/Send request again':''}}

From HR Department, {{ config('app.name') }}<br>
<br>
Mobile   : 0307-6823026<br>
LandLine : 0423-8937152, 0423-8910394 <br>

<p style="float: right;">Email Created On : {{Carbon\Carbon::now()->toDayDateTimeString()}} </p><br>
@endcomponent
