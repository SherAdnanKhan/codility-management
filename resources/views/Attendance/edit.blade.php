@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Add Informs</title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')
    @if (session('timetable'))
        <div class="alert alert-success">
            {{--{{ session('timetable') }}--}}
        </div>
    @endif
    <div class="container" style="margin-top: 5%;">
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4>Complete Your Attendance</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{route('attendance.update',$attendance->id)}}" >
                      {{method_field('PATCH')}}
                        {{ csrf_field() }}
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input type='text' id='check_in_time' name="check_in_time" {!! \Auth::user()->isAdmin()?"":"disabled ='disabled'"!!}  value="{{$attendance->check_in_time}}" class="input-material" />

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
                                <input type='text' id='check_out_time' name="check_out_time"   value="{{$attendance->check_out_time == false?'': $attendance->check_out_time}}" class="input-material" />

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
                                <input type='text' id='break_interval' name="break_interval"   value="{{$attendance->break_interval == false?'': $attendance->break_interval}}" class="input-material" />

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