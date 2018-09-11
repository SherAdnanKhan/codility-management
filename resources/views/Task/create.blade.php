@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Add Task</title>
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
                    <h4>Insert Task</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" id ="task" method="POST" action="{{route('task.store')}}" >
                        {{ csrf_field() }}
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input type='text' id='date' name="date"   value="{{old('date')}}" class="input-material" />

                                <label for="date" class="label-material">Task Date</label>
                            </div>
                                @if ($errors->has('date'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </span>
                                @endif
                        </div>
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input type='text' id='time_taken' name="time_taken"   value="{{old('time_taken')}}" class="input-material" />

                                <label for="time_taken" class="label-material">Time Taken For Task </label>
                            </div>
                            @if ($errors->has('time_taken'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('time_taken') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Brief Task</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea cols="10" name="description" class="form-control">
                                    {{old('description')}}
                                </textarea>
                            </div>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-outline-success">Schedule Task</button>
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
            $('#date').datetimepicker({
                format:'l'
            });
            $('#time_taken').datetimepicker({
                format:'hh:mm'
            });
        });
        $('#button_clear').click(function(){
            $('#task input[type="text"]').val('');
            $('#task textarea').val('');
        });


    </script>
@endsection