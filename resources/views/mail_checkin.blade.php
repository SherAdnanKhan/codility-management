@component('mail::message')
# Employees Attendance Status at {{Carbon\Carbon::now()->toDayDateTimeString()}}

@component('mail::panel')
These Employees are not marked CHECK IN or not present in Office Yet .
@endcomponent
@component('mail::table')
    |  Employee  Name     | Informed At                                                                                                                                                                                                                                     |        Informed Type                                                                            |   Inform status                               | Informed Reason              |
    | ------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- | --------------------------------------------- | ---------------------------- |
    @foreach($get_users_collection as $user)
        |     {{$user->name}}       |@php $inform=$user->informs()->whereBetween('attendance_date', [\Carbon\Carbon::now()->startOfDay()->timestamp, \Carbon\Carbon::now()->timestamp])->first(); @endphp {{$inform?$inform->inform_at:''}}|{{$inform->request_id !=null ? ($inform->get_request_leave->approved):''}}{{$inform?$inform->inform_type:''}}  {{$inform?($inform->leaves?' ON '.$inform->leaves->name:''):''}} | {{$inform?($inform->inform_late?'Yes Late Informed':'No Late Informed'):''}} | @php $reason=str_replace("<br />", "\n",$inform?$inform->reason:''); echo $reason; @endphp |
    @endforeach

@endcomponent
Thanks,
{{ config('app.name') }}
@endcomponent
