@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Task </title>
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
                                <a  class="btn btn-outline-success" href="{{route('task.create')}}"><span class="fa fa-plus"></span> Schedule Task</a>

                            </div>
                            <div class="col-lg-9 ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('task.search.employee')}}" >
                                    {{--{{ csrf_field() }}--}}
                                    <div class="row">
                                        <div class="form-group-material col-sm-2 ">
                                            <label for="inform_type" class="select-label form-control-label ">Search Task By</label>
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

                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='description' name="description"   value="{{\Request::get('description')}}" class="input-material" />

                                                <label for="name" class="label-material" style="left: 17px">Enter Task Description</label>
                                            </div>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-1 " style="margin-top: 27px;">
                                            <button type="submit" class="btn btn-outline-success">Search Task</button>
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
                                    <th>Task Date</th>
                                    <th>Task Created Date</th>
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
                                            <td>{{$task->date}}</td>
                                            <td>{{$task->created_at->diffForHumans()}}</td>
                                            <td>{{$task->time_take}}</td>
                                            <td>{{$task->project_id != null ?$task->projects->project_name:''}}{{$task->sub_project != null ?\App\SubProjectTask::where('id',$task->sub_project)->pluck('name'):''}}</td>
                                            <td>{{substr($task->description,0,50 )."..."}}</td>

                                            <td>
                                                <?php
                                                    $today=\Carbon\Carbon::now();
                                                    $date =\Carbon\Carbon::parse($task->date)->addHours(30);
                                                    $status =$date->gte($today);
                                                    ?>
                                                    @if($status == true)
                                                        <a href="{{route('task.edit',$task->id)}}"> <span class="fa fa-edit"></span></a>

                                                    @else
                                                        Time Has Spended
                                                @endif
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
    <div id="deleteMyModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DELETE INFORM</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">

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

    </script>
    <script>
        $(".delete_link").on('click',function() {
            var task=$(this).data("value");
            $.get('/task/'+ task +'/',function (result) {
                $('#modal-body').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/inform/'+result.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModal').modal();
                console.log(result);

            })

        });
    </script>
@endsection