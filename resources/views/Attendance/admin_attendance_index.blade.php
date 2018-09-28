@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Admin Attendance</title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Attendance</h1>
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
                            <div class="col-lg-9">
                                    <a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"
                                       href="#createMyModal">Mark Attendance</a>
                            </div>
                            <div class="col-lg-3">

                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-12">
                            <form class="filter_form" id ="filter_form" method="GET" action="{{route('attendance.search')}}" >
                                {{--{{ csrf_field() }}--}}
                                <div class="row">
                                    <div class="form-group-material col-sm-2 ">
                                        <label for="inform_type" class="select-label form-control-label ">Search Attendance By</label>
                                        <select name="filter" id="filter" class="form-control filters ">
                                            <option>Please Choose</option>

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

                                    <div class="form-group-material date_search col-sm-2" style="margin-top: 23px;">

                                        <div class='bootstrap-iso input-group-material' >
                                            <input type='text' id='start_date' name="start_date" value="{{old('start_date')}}" class="input-material" />

                                            <label for="start_date" style="left: 17px" class="label-material">Start Date Form</label>
                                        </div>
                                        @if ($errors->has('start_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                        @endif

                                    </div>
                                    <div class="form-group-material date_search col-sm-2" style="margin-top: 23px;">

                                        <div class=' bootstrap-iso input-group-material date' >
                                            <input type='text' id='end_date' name="end_date" value="{{old('end_date')}}" class="input-material" />

                                            <label for="end_date" style="left: 17px" class="label-material">End Date Form</label>
                                        </div>
                                        @if ($errors->has('end_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                        @endif

                                    </div>

                                    <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                        <div class='input-group-material'>
                                            <input type='text' id='name' name="name"   value="" class="input-material" />

                                            <label for="name" class="label-material" style="left: 17px">Employee Name (Optional)</label>
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-sm-1 " style="margin-top: 27px;">
                                        <button type="submit" class="btn btn-outline-success">Search Attendance</button>
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
                                    <th>Employee</th>
                                    <th>CheckIn (Date & Time)</th>
                                    <th>CheckOut (Date & Time)</th>
                                    <th>Break Interval</th>
                                    <th>Time Spent</th>
                                    <th>Status</th>
                                    <th>Status/Leave</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($attendances)
                                    @foreach($attendances as $attendance)
                                        <tr style="color: {{$attendance->attendance_type=='Late'?'red':'green'}}">
                                            <td>{{$attendance->user->name}}</td>
                                            <td>{{$attendance->check_in_time}}</td>
                                            <td>{{$attendance->check_out_time?$attendance->check_out_time:'Not Inserted'}}</td>
                                            <td>{{$attendance->break_interval?$attendance->break_interval:'Not Inserted'}}</td>
                                            <td>{{$attendance->time_spent}}</td>
                                            <td style= "color:{{$attendance->attendance_type == 'UnInformed Late'? 'red':''}}">{{$attendance->attendance_type}}
                                                @if($attendance->leave_comment){{$attendance->inform?$attendance->inform->inform_type =='LATE'?$attendance->inform->inform_type :'':''}}@endif
                                            </td>
                                            <td
                                            @if($attendance->leave_comment && $attendance->inform->inform_type=='LEAVE')
                                                style= "color:{{$attendance->inform->leaves->color_code}}"
                                                @elseif($attendance->leave_comment == null && $attendance->leave_id != null)
                                            style= "color:{{$attendance->leave->color_code}}"
                                            @endif
                                            >
                                                @if($attendance->leave_comment)
                                                    {{$attendance->inform?$attendance->inform->inform_type =='LEAVE'?$attendance->inform->leaves->name :'':''}}
                                                @elseif($attendance->leave_comment == null && $attendance->leave_id != null)
                                                    {{$attendance->leave->name}}
                                                    @else
                                                    No Concern
                                             @endif
                                            </td>
                                            <td class="text-primary lead">
                                                    <a href="{{route('attendance.edit',$attendance->id)}}">
                                                        <span class="fa fa-edit"></span>
                                                    </a>
                                                {{--<form class=form-inline" style="display: inline" method="POST"  action ="{{route('attendance.destroy',$attendance->id)}}"   enctype="multipart/form-data" >--}}
                                                    {{--{{method_field('DELETE')}}--}}
                                                    {{--{{ csrf_field()}}--}}
                                                     {{--<button class="form-submit  fa fa-times" type="submit" > </button>--}}
                                                {{--</form>--}}

                                                        <a  data-value="{{$attendance->id}}"  class="delete_link" href="#" >
                                                        <span class="fa fa-times"></span>
                                                    </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bootstrap-iso">
                        {{$attendances->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="createModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Mark Your Attendance</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{route('attendance.store')}}" >
                        {{ csrf_field() }}

                            <div class="form-group-material">
                                <div class=' bootstrap-iso input-group-material date' >
                                    <input type='text' id='check_in_time' name="check_in_time"   value="" class="input-material" />

                                    <label for="attendance" class="label-material">Attendance Date Time</label>
                                </div>
                                @if ($errors->has('check_in_time'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('check_in_time') }}</strong>
                                        </span>
                                @endif
                            </div>

                        <div class="form-group-material row">
                            <label for="inform_type" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Employee</label>
                            <div class="col-sm-12  mb-12 ">
                                <select name="employee"  class="form-control  ">
                                        <option value="" >Select Employee</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" >{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('employee'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('employee') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group-material row">
                            <label for="inform_type" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Inform Type</label>
                            <div class="col-sm-12  mb-12 ">
                                        <select name="inform_type"  class="form-control leavelink ">
                                    <option value="" >Select Inform Type</option>
                                    <option value="leave">Leave</option>
                                    <option value="check_in">Check IN</option>
                                </select>
                            </div>
                            @if ($errors->has('inform_type'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('inform_type') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div  class="form-group-material row view-leave " style="display: none">
                            <label for='leave_type' class='select-label col-sm-offset-3 col-sm-11 form-control-label '>Select Leave Type</label>
                            <div class='col-sm-12  mb-12 '>
                                <select name='leave_type'  class='form-control  getleave'>

                                </select>
                            </div>
                        </div>
                        <div class="form-group-material checkout" style="display: none">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input type='text' id='check_out_time' name="check_out_time"   value="" class="input-material" />

                                <label for="check_out_time" class="label-material">Check Out </label>
                            </div>
                            @if ($errors->has('check_out_time'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('check_out_time') }}</strong>
                                        </span>
                            @endif
                        </div>
                        <div class="form-group-material checkout" style="display: none">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input type='text' id='break_interval' name="break_interval"   value="" class="input-material" />

                                <label for="break_interval" class="label-material">Break Interval </label>
                            </div>
                            @if ($errors->has('break_interval'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('break_interval') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-outline-success">Mark Attendance</button>
                        <button type="button" id="button_clear" class="btn btn-outline-danger">
                            Reset
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <div id="deleteMyModals" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DELETE ATTENDANCE</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="delete">

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
            $('#check_out_time').datetimepicker({
                useCurrent: false
            });
            $('#check_in_time').datetimepicker({
                // minDate:new Date()
            });
            $('#break_interval').datetimepicker({
                format:'H:mm',

            });
            $("#check_in_time").on("dp.change", function (e) {
                $('#check_out_time').data("DateTimePicker").minDate(e.date);
            });
            $("#check_out_time").on("dp.change", function (e) {
                $('#check_in_time').data("DateTimePicker").maxDate(e.date);
            });
        });
        $('#button_clear').click(function(){
            $('#timetable input[type="text"]').val('');
            $('#timetable input[type="checkbox"]').prop('checked', false);
        });
        $(".leavelink").change(function() {
            if ($(this).val() == 'leave') {
                $.get('/leaves',function (result) {
                    for(var i = 0; i < result.length; i++) {
                        var leave = result[i];
                        console.log(leave.id);
                        $(".getleave").append("<option value="+leave.id+" >"+leave.name+"</option>");
                    }
                    $(".view-leave").css('display','block');
                    $(".checkout").css('display','none');
                })
            }
            if ($(this).val() == 'check_in') {
                $(".checkout").css('display','block');
                $(".view-leave").css('display','none');
            }
        });
        $(function () {
            // var FromEndDate = new Date();
            $('#start_date').datetimepicker({format:'L',

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
        $(function () {
            $('#date').datetimepicker({
                format:'l',
            });
            $('#time_taken').datetimepicker({
                format:'hh:mm'
            });
        });
        $(".delete_link").on('click',function() {
            var attendance=$(this).data("value");
            $.get('/attendance/'+ attendance +'/',function (result) {
                $('#delete').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/attendance/'+result+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModals').modal();
                console.log(result);

            })
        });
    </script>
@endsection