@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Add Task</title>
@endsection
@section('page_styles')

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
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
                    <h4>Update Task</h4>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" id ="task" method="POST" action="{{route('task.update',$task->id)}}" >
                        {{method_field('PATCH')}}
                        {{ csrf_field() }}
                        @if(\Auth::user()->isAdmin())
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input autocomplete="off" type='text' id='date' name="date"   value="{{$task->date}}" class="input-material" />

                                <label for="date" class="label-material">Task Date</label>
                            </div>
                            @if ($errors->has('date'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                            @endif
                        </div>
                            <div class="form-group-material row">
                                <label for="project" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">
                                    Project</label> <div class="col-sm-12 mb-12 ">
                                    <select name="project_id" class="form-control delete_link">
                                        <option value="" >Select Project</option>
                                        {{$projects=\App\ProjectTask::all()}}
                                        @foreach($projects as $project)
                                            <option {{$project->id == $task->project_id ?"selected='selected'":''}} value="{{$project->id}}" >{{$project->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('project_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('project_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        @if($task->sub_project != null)
                            <div  class="form-group-material row view-first" >
                                <label for='leave_type' class='select-label col-sm-offset-3 col-sm-11 form-control-label view_label'>Select Sub Projects</label>
                                <div class='col-sm-12  mb-12 '>
                                    <select name='sub_projects'  class='form-control  ajax'>
                                        <option value="">Select Sub Project</option>
                                        {{$subprojects=\App\SubProjectTask::all()}}
                                        @foreach($subprojects as $subproject)
                                            <option {{$task->sub_project != null ? ($subproject->id == $task->sub_project ?"selected='selected'":''):''}}value="{{$subproject->id}}" >{{$subproject->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div  class="form-group-material row view" style="display: none">
                                <label for='leave_type' class='select-label col-sm-offset-3 col-sm-11 form-control-label view_label'>Select Sub Projects</label>
                                <div class='col-sm-12  mb-12 '>
                                    <select name='sub_projects'  class='form-control  ajax'>
                                        <option value="">Select Sub Project</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input autocomplete="off" type='text' id='time_taken' name="time_taken"   value="{{$task->time_take}}" class="input-material" />

                                <label for="time_taken" class="label-material">Time Taken For Task </label>
                            </div>
                            @if ($errors->has('time_taken'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('time_taken') }}</strong>
                                    </span>
                            @endif
                        </div>
                        @if(\Auth::user()->isAdmin())
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Brief Task</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="description" class="form-control">{{$task->description}}</textarea>
                            </div>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        @endif
                        <button type="submit" class="btn btn-outline-success">Update Task</button>
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
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(".delete_link").change(function() {
            var project=$(this).val();

            $.get('/project/project/'+project,function (result) {
                if (result.length == 0){
                    $(".view-first").css('display','none');
                    $(".view").css('display','none');
                    // $(".ajax").css('display','none');
                    // $(".view_label").css('display','none');


                }else {
                    for (var i = 0; i < result.length; i++) {
                        var sub_projects = result[i];
                        console.log(sub_projects.id);
                        $(".ajax").append("<option value=" + sub_projects.id + " >" + sub_projects.name + "</option>");
                    }
                    console.log(result.length);
                    $(".view").css('display','block');
                }


            })

        });
        $(function () {
            $('#date').datetimepicker({
                format:'l'
            });
            $('#time_taken').datetimepicker({
                format:'H:mm'
            });
        });
        $('#button_clear').click(function(){
            $('#task input[type="text"]').val('');
            $('#task textarea').val('');
        });


    </script>
@endsection