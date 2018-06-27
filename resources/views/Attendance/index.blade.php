@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Attendance</title>
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
                        <div class="row">
                            <div class="col-lg-9">
                               @if(empty($today))
                                <a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"
                                   href="#createMyModal">Mark Attendance</a>
                                    @endif


                            </div>
                            <div class="col-lg-3">
                                <div class="input-group input-group-md">
                                    <input class="form-control" placeholder="Search by Name" type="text">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-success ">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>CheckIn (Date & Time)</th>
                                    <th>CheckOut (Date & Time)</th>
                                    <th>Break Interval</th>
                                    <th>Time Spent</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($attendances)
                                    @foreach($attendances as $attendance)
                                            <tr>
                                                <td>{{$attendance->check_in_time}}</td>
                                                <td>{{$attendance->check_out_time?$attendance->check_out_time:'Not Inserted'}}</td>
                                                <td>{{$attendance->break_interval?$attendance->break_interval:'Not Inserted'}}</td>
                                                <td>{{$attendance->time_spent}}</td>
                                                <td>{{$attendance->attendance_type}}</td>
                                                <td class="text-primary lead">
                                                    @if($attendance->check_in_time && (!($attendance->check_out_time)))
                                                    <a href="{{route('attendance.edit',$attendance->id)}}"><span class="fa fa-edit"></span></a>
                                                </td>
                                                @endif
                                            </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
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

                                <label for="check_in_time" class="label-material">Check In </label>
                            </div>
                            @if ($errors->has('check_in_time'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('check_in_time') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group-material">
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
                        <div class="form-group-material">
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
@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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

    </script>
@endsection