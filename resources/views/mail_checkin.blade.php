@component('mail::message')
# Employees Attendance Status at {{Carbon\Carbon::now()->toDayDateTimeString()}}

@if(isset($get_users_collection))
    @foreach($get_users_collection as $user)
        @php
            $inform=$user->informs()->whereBetween('attendance_date', [\Carbon\Carbon::now()->startOfDay()->timestamp, \Carbon\Carbon::now()->timestamp])->first();
        @endphp
        @if($inform->inform_type == "LEAVE")
            @php $informleave=true;@endphp
        @endif
        @if($inform->inform_type == "LATE")
            @php $informlate=true;@endphp
        @endif
    @endforeach
@endif
@if(isset($get_users_collection))
    @if(isset($informleave))
 The following list having those employees who have Informed Leave
@component('mail::table')
    |  Employee  Name     | Informed At                                                                                                                                                                                                                                     |        Informed Type                                                                            | Informed Reason              |
    | ------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- | ---------------------------- |
    @foreach($get_users_collection as $user)
        @php
            $inform=$user->informs()->whereBetween('attendance_date', [\Carbon\Carbon::now()->startOfDay()->timestamp, \Carbon\Carbon::now()->timestamp])->first();
        @endphp
        @if($inform->inform_type == "LEAVE")
            |     <p class="heading_">{{$user->name}}<p class="heading_"> | {{$inform?\Carbon\Carbon::parse($inform->inform_at)->format('M ,d g:i A'):''}}|{{$inform?($inform->request_id != null ? ($inform->get_request_leave->approved):''):''}}{{$inform?$inform->inform_type:''}}  {{$inform?($inform->leaves?' ON '.$inform->leaves->name:''):''}} | @php $reason=str_replace("<br />", "\n",$inform?$inform->reason:''); @endphp <p class="heading_">{{$reason}} </p>  |
        @endif
    @endforeach
@endcomponent

@endif
@endif

@if(isset($get_users_collection))
@if(isset($informlate))

The following list having those employees who have Informed Late
@component('mail::table')
        |  Employee  Name     | Informed At                                                                                                       | Informed Reason             |
        | ------------------- | ----------------------------------------------------------------------------------------------------------------- |---------------------------- |
        @foreach($get_users_collection as $user)
            @php
                $inform=$user->informs()->whereBetween('attendance_date', [\Carbon\Carbon::now()->startOfDay()->timestamp, \Carbon\Carbon::now()->timestamp])->first();
            @endphp
            @if($inform->inform_type == "LATE")
                |    <p class="heading_"> {{$user->name}}</p>       |{{$inform?\Carbon\Carbon::parse($inform->inform_at)->format('M ,d g:i A'):''}}| @php $reason=str_replace("<br />", "\n",$inform?$inform->reason:''); @endphp <p class="heading_">{{$reason}} </p> |
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
            |     <p class="heading_">{{$uninform_user->name}}</p> |
        @endforeach
    
@endcomponent

@endif



Thanks,
{{ config('app.name') }}
@endcomponent
