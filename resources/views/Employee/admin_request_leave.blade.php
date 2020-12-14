@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Leave Request List </title>
@endsection
@section('page_styles')
    
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Employee Leave Request List</h1>
        </header>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-lg-3">
                            
                            </div>
                            <div class="col-lg-9 ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('request.search')}}" >
                                    {{--{{ csrf_field() }}--}}
                                    <div class="row">
                                        <div class="form-group-material col-sm-2 ">
                                            <label for="inform_type" class="select-label form-control-label ">Search Leave Request </label>
                                            <select name="filter" id="filter" class="form-control filters ">
                                                <option value="">Please Choose</option>
                                                <option {{\Request::get('filter')=='approved'?'selected ':''}}value="approved">Approved</option>
                                                <option {{\Request::get('filter')=='not_approved'?'selected ':''}}value="not_approved">Not Approved </option>
                                                <option {{\Request::get('filter')=='declined'?'selected ':''}}value="declined">Declined </option>
                                                <option {{\Request::get('filter')=='today'?'selected ':''}}value="today">Today</option>
                                                <option {{\Request::get('filter')=='week'?'selected ':''}}value="week">This Week</option>
                                                <option {{\Request::get('filter')=='month'?'selected ':''}}value="month">This Month</option>
                                                <option {{\Request::get('filter')=='year'?'selected ':''}}value="year">This Year</option>
                                                <option {{\Request::get('filter')=='custom'?'selected ':''}}value="custom">Custom</option>

                                            </select>

                                            @if ($errors->has('filter'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('filter') }}</strong>
                                    </span>
                                            @endif
                                        </div>

                                        <div class="form-group-material date_search col-sm-2" style="margin-top: 23px; <?= \Request::get('filter')=='custom'? 'display:block':''?>">

                                            <div class='bootstrap-iso input-group-material' >
                                                <input autocomplete="off" type='text' id='start_date' name="start_date" value="{{\Request::get('start_date')?\Request::get('start_date'):''}}" class="input-material" />

                                                <label for="start_date" style="left: 17px" class="label-material">Start Date Form</label>
                                            </div>
                                            @if ($errors->has('start_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                            @endif

                                        </div>
                                        <div class="form-group-material date_search col-sm-2" style="margin-top: 23px; <?= \Request::get('filter')=='custom'? 'display:block':''?>">

                                            <div class=' bootstrap-iso input-group-material date' >
                                                <input autocomplete="off" type='text' id='end_date' name="end_date" value="{{\Request::get('end_date')?\Request::get('end_date'):''}}" class="input-material" />

                                                <label for="end_date" style="left: 17px" class="label-material">End Date Form</label>
                                            </div>
                                            @if ($errors->has('end_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                            @endif

                                        </div>
                                        {{--<div class="form-group-material col-sm-3 "style="margin-top: 23px;">--}}
                                            {{--<div class='input-group-material'>--}}
                                                {{--<input type='text' id='name' name="name"   value="{{\Request::get('name')?\Request::get('name'):''}}" class="input-material" />--}}
            {{----}}
                                                {{--<label for="name" class="label-material" style="left: 17px">Employee Name (Optional)</label>--}}
                                            {{--</div>--}}
                                            {{--@if ($errors->has('name'))--}}
                                                {{--<span class="help-block">--}}
                                            {{--<strong>{{ $errors->first('name') }}</strong>--}}
                                            {{--</span>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                        <div class="col-sm-1 " style="margin-top: 27px;">
                                            <button type="submit" class="btn btn-outline-success">Search Leave Request</button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Leave From</th>
                                    <th>Leave TO</th>
                                    <th>Leave Approved AS</th>
                                    <th>Leave Reason</th>
                                    <th>Approved Status</th>
                                    <th>Marked Approved</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($request_leaves))
                                    @foreach($request_leaves as $request_leave)
                                        <tr>
                                            <td>{{$request_leave->get_user->name}}</td>
                                            <td>{{\Carbon\Carbon::createFromTimestamp($request_leave->from_date)->format('d-m-Y')}}</td>
                                            <td>{{$request_leave->to_date != null ? (\Carbon\Carbon::createFromTimestamp($request_leave->to_date)->format('d-m-Y')):''}}</td>
                                            <td>{{$request_leave->leave_id != null ? $request_leave->get_leaves->name:''}}</td>
                                            <td>{{$request_leave->reason}}</td>
                                            <td>{{$request_leave->approved}}</td>
                                            <td><a  style="color:green "data-value="{{$request_leave->id}}"  class="edit_link" href="#" >
                                                    <span class="fa fa-edit"></span>
                                                </a></td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bootstrap-iso">
                        {{$request_leaves->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="updateModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">APPROVED LEAVE REQUEST</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="update-modal-body">
            
            
                </div>
        
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            // var FromEndDate = new Date();
            $('#start_date').datetimepicker({format:'L'
            });
            $('#end_date').datetimepicker({
                format:'L',
            });
        });
        $("#start_date").on("dp.change", function (e) {

            $('#end_date').data("DateTimePicker").minDate(e.date);
        });
        $("#end_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
        $("#filter").change(function() {

            if($(this).val() =='custom'){
                $('.date_search').css('display','block');
            }if($(this).val() != 'custom'){
                $('.date_search').css('display','none');
            }

        });
        $(".edit_link").on('click',function() {
            var leave_request=$(this).data("value");

            $.get('/approval/request/'+leave_request,function (data) {
                // $.each(data,function (index,state) {
                $('#update-modal-body').html(data);
                $('#updateModal').modal();
                console.log(data);

            })

        });
        // $('#update-modal-body').on("click", '#approved', function(event) {
        //     var approved=$('#approved').val();
        //     // if (approved == 'on'){
        //     // $('.decline_group').hide();}
        //     // else{
        //     //     $('.decline_group').show();
        //     //
        //     // }
        //     alert(approved);
        // });
    </script>
    
@endsection
