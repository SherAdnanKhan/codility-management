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
                    <h4> Manage Informs</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{route('inform.store')}}" >
                        {{ csrf_field() }}
                        {{--@foreach($timetable as $time)--}}
                            <div class="form-group-material">
                                <div class=' bootstrap-iso input-group-material date' >
                                    <input type='text' id='attendance_date' name="attendance_date" autocomplete="off"  value="{{old('attendance_date')}}" class="input-material" />

                                    <label for="attendance_date" class="label-material">Attendance Date </label>
                                </div>
                                @if ($errors->has('attendance_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('attendance_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input autocomplete="off" type='text' id='inform_at' name="inform_at"   value="{{old('inform_at')}}" class="input-material" />

                                <label for="attendance_date" class="label-material">Inform Time </label>
                            </div>
                            @if ($errors->has('inform_at'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('inform_at') }}</strong>
                                    </span>
                            @endif

                        </div>
                        <div class="form-group-material row">
                            <label for="inform_type" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Inform Type</label>
                            <div class="col-sm-12  mb-12 ">
                                <select name="inform_type"  class="form-control delete_link ">
                                    <option value="" >Select Inform Type</option>
                                    <option value="leave">Leave</option>
                                    <option value="late">Late</option>
                                </select>
                            </div>
                                @if ($errors->has('inform_type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('inform_type') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div  class="form-group-material row view" style="display: none">
                            <label for='leave_type' class='select-label col-sm-offset-3 col-sm-11 form-control-label '>Select Leave Type</label>
                            <div class='col-sm-12  mb-12 '>
                                <select name='leave_type'  class='form-control  ajax'>
                                <option value="">Select Leave Type</option>
                                </select>
                        </div>
                        
                        </div>
                        @if ($errors->has('leave_type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('leave_type') }}</strong>
                                    </span>
                        @endif
                        <div class="form-group-material row">
                            <label for="employee" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Employees</label>
                            <div class="col-sm-12  mb-12 ">
                                <select name="employee" class="form-control">
                                    <option value="" >Select Employee</option>
                                    @foreach($user as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('employee'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('employee') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Reason</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="reason" class="form-control">{{old('reason')}}</textarea>
                            </div>
                            @if ($errors->has('reason'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group row of-button" >
                            <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Late Informed</label>

                            <label class="switch" class="col-sm-offset-3 ">
                            <input type="checkbox" name="late_informed" >
                            <span class="slider round"></span>
                        </label>
                        </div>
                        <button type="submit" class="btn btn-outline-success">Schedule Inform</button>
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
    <script >
        $(".delete_link").change(function() {
            if ($(this).val() == 'leave') {
            $.get('/leaves',function (result) {
                for(var i = 0; i < result.length; i++) {
                    var leave = result[i];
                    console.log(leave.id);
                    $(".ajax").append("<option value="+leave.id+" >"+leave.name+"</option>");
                }
                    $(".view").css('display','block');
            })
            }
            if ($(this).val() == 'late') {
                $(".view").css('display','none');
            }
        });
    </script>
    <script src="{{asset('scripts/moment.js')}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#inform_at').datetimepicker({});
            $('#attendance_date').datetimepicker({
                    format:'l'
            });
        });
        $('#button_clear').click(function(){
            $('#timetable input[type="text"]').val('');
            $('#timetable input[type="checkbox"]').prop('checked', false);
            $('#timetable select').prop('value', '');
        });


    </script>
@endsection
