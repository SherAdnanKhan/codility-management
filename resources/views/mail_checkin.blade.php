@component('mail::message')
# Employees Attendance Status at {{Carbon\Carbon::now()->toDayDateTimeString()}}

@component('mail::panel')
Following employees are not marked CHECK IN or not present in office Yet .
@endcomponent
@if(isset($get_users_collection))
 The following list having those employees who have Informed Leave
@component('mail::table')
    |  Employee  Name     | Informed At                                                                                                                                                                                                                                     |        Informed Type                                                                            |   Inform status                               | Informed Reason              |
    | ------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- | --------------------------------------------- | ---------------------------- |
    @foreach($get_users_collection as $user)
        @php
            $inform=$user->informs()->whereBetween('attendance_date', [\Carbon\Carbon::now()->startOfDay()->timestamp, \Carbon\Carbon::now()->timestamp])->first();
        @endphp
        @if($inform->inform_type == "LEAVE")
        |     {{$user->name}}       | {{$inform?\Carbon\Carbon::parse($inform->inform_at)->format('M ,d g:i A'):''}}|{{$inform?($inform->request_id != null ? ($inform->get_request_leave->approved):''):''}}{{$inform?$inform->inform_type:''}}  {{$inform?($inform->leaves?' ON '.$inform->leaves->name:''):''}} | {{$inform?($inform->inform_late?'Yes Late Informed':'No Late Informed'):''}} | @php $reason=str_replace("<br />", "\n",$inform?$inform->reason:''); echo $reason; @endphp |
        @endif
        @if($inform->inform_type == "LATE")
        @php $informlate=true;@endphp
        @endif
    @endforeach

    
@endcomponent

@endif

@if(isset($get_users_collection))
    @if(isset($informlate))
 The following list having those employees who have Informed Late
@component('mail::table')
        |  Employee  Name     | Informed At                                                                                                                                                                                                                                     |        Informed Type                                                                            |   Inform status                               | Informed Reason              |
        | ------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- | --------------------------------------------- | ---------------------------- |
        @foreach($get_users_collection as $user)
            @php
                $inform=$user->informs()->whereBetween('attendance_date', [\Carbon\Carbon::now()->startOfDay()->timestamp, \Carbon\Carbon::now()->timestamp])->first();
            @endphp
            @if($inform->inform_type == "LATE")
                |     {{$user->name}}       |{{$inform?\Carbon\Carbon::parse($inform->inform_at)->format('M ,d g:i A'):''}}|{{$inform?($inform->request_id != null ? ($inform->get_request_leave->approved):''):''}}{{$inform?$inform->inform_type:''}}  {{$inform?($inform->leaves?' ON '.$inform->leaves->name:''):''}} | {{$inform?($inform->inform_late?'Yes Late Informed':'No Late Informed'):''}} | @php $reason=str_replace("<br />", "\n",$inform?$inform->reason:''); echo $reason; @endphp |
            @endif
        @endforeach
    
    
@endcomponent

@endif
@endif
@if(isset($uninform))
 The following list having those employees who have Un-informed late
@component('mail::table')
        |  Employee  Name     |
        | ------------------- |
        @foreach($uninform as $uninform_user)
        |     {{$uninform_user->name}} |
        @endforeach
    
@endcomponent

@endif



Thanks,
{{ config('app.name') }}
@endcomponent
