@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Task </title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Task Management</h1>
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
                                <a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"
                                   href="#createMyModal"><span class="fa fa-plus"></span> Employee Task</a>
                            </div>
                            <div class="col-lg-9 hidden-print">
                                    <div class="" style=" float: right;margin-bottom: -30px">
                                        <button type="button" class="btn btn-outline-dark  download"><span class="fa fa-print"></span> Download Csv</button>
                                    </div>
                                </form>
    
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('task.search')}}" >
                                    {{--{{ csrf_field() }}--}}
                                <div class="row">
                                <div class="form-group-material col-sm-2 ">
                                    <label for="inform_type" class="select-label form-control-label ">Search Task By</label>
                                        <select name="filter" id="filter" class="form-control filters ">
                                            <option value="">Please Choose</option>

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
                                    <div class="form-group-material date_search col-sm-2" style="margin-top: 23px;<?= \Request::get('filter')=='custom'? 'display:block':''?>">

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
                                    <div class="appendDownloadCsv">
                                    
                                    </div>
                                <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                    <div class='input-group-material'>
                                        <input type='text' id='name' name="name"   value="{{\Request::get('name')?\Request::get('name'):''}}" class="input-material" />

                                        <label for="name" class="label-material" style="left: 17px">Employee Name (Optional)</label>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                    @endif
                                </div>
                                    <div class="col-sm-1 " style="margin-top: 27px;">
                                    <button type="submit" class="btn btn-outline-success search_task">Search Task</button>
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
                                    <th>Task Date</th>
                                    <th>Task Created</th>
                                    <th>Task Timing</th>
                                    <th>Project</th>
                                    <th>Task Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($tasks)
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td>{{$task->user->name}}</td>
                                            <td>{{$task->date}}</td>
                                            <td>{{$task->created_at->diffForHumans()}}</td>
                                            <td>{{$task->time_take}}</td>
                                            <td>{{$task->project_id != null ?$task->projects->project_name:''}}{{$task->sub_project != null ?\App\SubProjectTask::where('id',$task->sub_project)->pluck('name'):''}}</td>
                                            <td>{{substr($task->description,0,50 )."..."}}</td>
                                            <td><a href="{{route('task.edit',$task->id)}}"> <span class="fa fa-edit"></span>
                                                </a>
                                                <a  data-value="{{$task->id}}"  class="show_link" href="#" >
                                                    <span class="fa fa-file"></span>
                                                </a>
                                                <a  data-value="{{$task->id}}"  class="delete_link" href="#" >
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
                    {{$tasks->links()}}
                            </div>
                </div>
            </div>
        </div>
    </div>
    <div id="deleteMyModals" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DELETE TASK</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="delete">

                </div>

            </div>
        </div>
    </div>
    <div id="showMyModals" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DISPLAY TASK DETIAL</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="show_body">

                </div>

            </div>
        </div>
    </div>

    <div id="createModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Schedule Employee Task</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id ="task" method="POST" action="{{route('task.store')}}" >
                        {{ csrf_field() }}
                        <div class="form-group-material row">
                            <label for="inform_type" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">
                                Employee</label> <div class="col-sm-12 mb-12 ">
                                <select name="employee" class="form-control ">
                                    <option value="" >Select Employee</option>
                                    {{$users=\App\User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->where('abended',0)->get()}}
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
                            <label for="project" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">
                                Project</label> <div class="col-sm-12 mb-12 ">
                                <select  name="project_id" class="form-control delete_links">
                                    <option value="" >Select Project</option>
                                    {{$projects=\App\ProjectTask::all()}}
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}" >{{$project->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('project_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('project_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div  class="form-group-material row view" style="display: none">
                            <label for='leave_type' class='select-label col-sm-offset-3 col-sm-11 form-control-label view_label'>Select Sub Projects</label>
                            <div class='col-sm-12  mb-12 '>
                                <select name='sub_projects'  class='form-control  ajax'>
                                    <option value="">Select Sub Projects</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-material">
                            <div class=' bootstrap-iso input-group-material date' >
                                <input autocomplete="off" type='text' id='date' name="date"   value="{{old('date')}}" class="input-material" />

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
                                <input autocomplete="off" type='text' id='time_taken' name="time_taken"   value="{{old('time_taken')}}" class="input-material" />

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
                                <textarea  name="description" class="form-control">{{old('description')}}</textarea>
                            </div>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-outline-success">Schedule Employee Task</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection
@section('page_scripts')
    <script>

        $(".download").on('click',function() {
            
            $('.appendDownloadCsv').html("<input type='hidden' class='download_display_none' id='download' name='download'   value='download'  />");
            $('.filter_form').submit();
        });
        $(".search_task").on('click',function() {

            $('.download_display_none').val('');
            $('.filter_form').submit();
        });
        $(".delete_link").on('click',function() {
            var task=$(this).data("value");
            $.get('/delete-task/'+ task +'/',function (result) {
            $('#delete').html("Are You Sure Delete "  +
            '<form class=form-inline" method="POST"  action ="/task/'+result.id+'"   enctype="multipart/form-data" >' +
            '{{method_field('DELETE')}}' +
            '{{ csrf_field()}}'+
            ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
            $('#deleteMyModals').modal();
            console.log(result);

            })
        });
        $(".delete_links").change(function() {
            var project=$(this).val();

            $.get('/project/project/'+project,function (result) {
                if (result.length == 0){
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
        $(".show_link").on('click',function() {
            var task=$(this).data("value");
            $.get('/task/'+ task +'/',function (result) {
                $('#show_body').html('<div class=""> <div class=""> <div class="card custom-card"> <div class="card-body">'+
                '<p class="card-text">Task Date :'+result.date+'</p>'+
                '<p class="card-text">Task Time :'+result.time_take+'</p>'+
                    '</div>'+
                    '<div class="card-footer text-center">'+
                    '<i class="info">Description : '+result.description+'</i>'+
                    '</div>'+
                    '</div>'+
                    '</div>');
                $('#showMyModals').modal();
                console.log(result);

            })
        });
    </script>
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
        $(function () {
            $('#date').datetimepicker({
                format:'l',
            });
            $('#time_taken').datetimepicker({
                format:'H:mm'
            });
        });
        $('#button_clear').click(function(){
            $('#timetable input[type="text"]').val('');
            $('#timetable textarea').val('');
        });
    </script>


@endsection
