@component('mail::message')
    # {{isset($get_leave_request)?$get_leave_request->get_user->name:''}}`s Request For Leave Approval
    
@component('mail::panel')
{{isset($get_leave_request)?$get_leave_request->get_user->name:''}} Request For Leave Approval on the following dates with reason
@endcomponent
@component('mail::table')
        |  Employee  Name                      | Leave From                                                                            | Leave To                                                                            | Reason of Leave              |
        | ------------------------------------ | ------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ---------------------------- |
        @if(isset($get_leave_request))
        |{{$get_leave_request->get_user->name}} | {{\Carbon\Carbon::createFromTimestamp($get_leave_request->from_date)->format('d-m-Y')}} | {{$get_leave_request->to_date != null ?(\Carbon\Carbon::createFromTimestamp($get_leave_request->to_date)->format('d-m-Y')):''}} | {{$get_leave_request->reason}} |
        @endif
@endcomponent
    Thanks,
    {{ config('app.name') }}
@endcomponent
