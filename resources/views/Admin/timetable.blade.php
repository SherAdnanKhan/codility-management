@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Manage Time</title>
@endsection
@section('page_styles')

<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">

@endsection
@section('body')
    @if (session('timetable'))
        <div class="alert alert-success">
            {{ session('timetable') }}
        </div>
    @endif
    <div class="container row" style="margin: auto; margin-top: 5%;">
        <div class="Register-form col-md-6 col-md-offset-1" style="background-color: #fff;">
            <div class="form-inner flex-fill ">
                <div class="card-header">
                    <h4> Morning Shift TimeTable</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{ route('timetable.store') }}">
                        {{ csrf_field() }}
                        {{--@foreach($morning_timetabletable as $morning_timetable)--}}
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='start_time' name="start_time"   value="{{$morning_timetable->start_time}}" class="input-material" />
                                    @if ($errors->has('start_time'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('start_time') }}</strong>
                                    </span>
                                    @endif
                                    <label for="start_time" class="label-material">Start Time</label>
                                </div>
                            </div>
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='end_time' value="{{$morning_timetable->end_time}}" name="end_time" class="input-material" />
                                    @if ($errors->has('end_time'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('end_time') }}</strong>
                                    </span>
                                    @endif
                                    <label for="end_time" class="label-material">End Time</label>
                                </div>
                            </div>
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='working_hour' value="{{$morning_timetable->working_hour}}" name ="working_hour" class="input-material" />
                                    @if ($errors->has('working_hour'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('working_hour') }}</strong>
                                    </span>
                                    @endif
                                    <label for="working_hour" class="label-material">Working Hours</label>
                                </div>
                            </div>
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='non_working_hour'  name ="non_working_hour" class="input-material" value="{{$morning_timetable->non_working_hour}}"/>
                                    @if ($errors->has('non_working_hour'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('non_working_hour') }}</strong>
                                    </span>
                                    @endif
                                    <label for="non_working_hour" class="label-material">Non Working Hours</label>
                                </div>
                            </div>

                            <div class="form-group-material allowed_days">
                                <label class=" label-material" style="font-weight: lighter">Days Allowed</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">
                                        <input id="monday" name="monday" {{$morning_timetable->monday  ? 'checked':''}} type="checkbox" value="1"> Monday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="tuesday"  name="tuesday" {{$morning_timetable->tuesday  ? 'checked':''}} type="checkbox" value="2"> Tuesday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="wednesday" name="wednesday" {{$morning_timetable->wednesday  ? 'checked':''}} type="checkbox" value="3"> Wednesday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="thursday" name="thursday" {{$morning_timetable->thursday  ? 'checked':''}} type="checkbox" value="4"> Thursday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="friday"  name="friday" {{$morning_timetable->friday  ? 'checked':''}} type="checkbox" value="5">Friday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="saturday" name="saturday" {{$morning_timetable->saturday  ? 'checked':''}} type="checkbox" value="6"> Saturday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="sunday" name="sunday" {{$morning_timetable->sunday  ? 'checked':''}} type="checkbox" value="6"> Sunday
                                    </label>

                                </div>
                            </div>
                        {{--@endforeach--}}
                        <button type="submit" class="btn btn-outline-success">Schedule Time</button>
                        <button type="button" id="button_clear" class="btn btn-outline-danger">
                            Reset
                        </button>
                    </form>

                </div>
            </div>
        </div>
        <div class="Register-form col-md-6 " style="background-color: #fff;">
            <div class="form-inner flex-fill ">
                <div class="card-header">
                    <h4> Evening Shift TimeTable</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{ route('timetable.evening.store') }}">
                        {{ csrf_field() }}
                        {{--@foreach($timetable as $time)--}}

                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='evening_start_time' name="evening_start_time"   value="{{$evening_timetable->start_time}}" class="input-material" />
                                    @if ($errors->has('evening_start_time'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('evening_start_time') }}</strong>
                                    </span>
                                    @endif
                                    <label for="evening_start_time" class="label-material">Start Time</label>
                                </div>
                            </div>
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='evening_end_time' value="{{$evening_timetable->end_time}}" name="evening_end_time" class="input-material" />
                                    @if ($errors->has('evening_end_time'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('evening_end_time') }}</strong>
                                    </span>
                                    @endif
                                    <label for="evening_end_time" class="label-material">End Time</label>
                                </div>
                            </div>
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='evening_working_hour' value="{{$evening_timetable->working_hour}}" name ="evening_working_hour" class="input-material" />
                                    @if ($errors->has('evening_working_hour'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('evening_working_hour') }}</strong>
                                    </span>
                                    @endif
                                    <label for="evening_working_hour" class="label-material">Working Hours</label>
                                </div>
                            </div>
                            <div class="form-group-material">
                                <div class='input-group-material date' >
                                    <input autocomplete="off" type='text' id='evening_non_working_hour'  name ="evening_non_working_hour" class="input-material" value="{{$evening_timetable->non_working_hour}}" />
                                    @if ($errors->has('evening_non_working_hour'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('evening_non_working_hour') }}</strong>
                                    </span>
                                    @endif
                                    <label for="evening_non_working_hour" class="label-material">Non Working Hours</label>
                                </div>
                            </div>

                            <div class="form-group-material allowed_days">
                                <label class=" label-material" style="font-weight: lighter">Days Allowed</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">
                                        <input id="monday" name="monday" {{$evening_timetable->monday  ? 'checked':''}} type="checkbox" value="1"> Monday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="tuesday"  name="tuesday" {{$evening_timetable->tuesday  ? 'checked':''}} type="checkbox" value="2"> Tuesday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="wednesday" name="wednesday" {{$evening_timetable->wednesday  ? 'checked':''}} type="checkbox" value="3"> Wednesday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="thursday" name="thursday" {{$evening_timetable->thursday  ? 'checked':''}} type="checkbox" value="4"> Thursday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="friday"  name="friday" {{$evening_timetable->friday  ? 'checked':''}} type="checkbox" value="5">Friday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="saturday" name="saturday" {{$evening_timetable->saturday  ? 'checked':''}} type="checkbox" value="6"> Saturday
                                    </label>
                                    <label class="checkbox-inline">
                                        <input id="sunday" name="sunday" {{$evening_timetable->sunday  ? 'checked':''}} type="checkbox" value="6"> Sunday
                                    </label>

                                </div>
                            </div>
                        {{--@endforeach--}}
                        <button type="submit" class="btn btn-outline-success">Schedule Time</button>
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
<script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $('#non_working_hour').datetimepicker({format:'H:mm'})
        $('#evening_non_working_hour').datetimepicker({format:'H:mm'})

        $('#start_time').datetimepicker({format: 'LT', format: 'hh:mm A'});
        $('#working_hour').datetimepicker({format: 'LT', format: 'hh:mm'});

        $('#end_time').datetimepicker({format: 'LT', format: 'hh:mm A', useCurrent: false });
        $("#start_time").on("dp.change", function (e) {
            $('#end_time').data("DateTimePicker").minDate(e.date);
        });
        $("#end_time").on("dp.change", function (e) {
            $('#start_time').data("DateTimePicker").maxDate(e.date);
        });
        $('#evening_start_time').datetimepicker({format: 'LT', format: 'hh:mm A'});
        $('#evening_working_hour').datetimepicker({format: 'LT', format: 'hh:mm'});

        $('#evening_end_time').datetimepicker({format: 'LT', format: 'hh:mm A', useCurrent: false });
        $("#evening_start_time").on("dp.change", function (e) {
            $('#evening_end_time').data("DateTimePicker").minDate(e.date);
        });
        $("#evening_end_time").on("dp.change", function (e) {
            $('#evening_start_time').data("DateTimePicker").maxDate(e.date);
        });
    });
    $('#button_clear').click(function(){
        $('#timetable input[type="text"]').val('');
        $('#timetable input[type="checkbox"]').prop('checked', false);
    });
</script>
@endsection