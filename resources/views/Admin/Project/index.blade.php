@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Employee Projects</title>
@endsection
@section('page_styles')
    
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-tagsinput.css')}}">
@endsection
@section('body')
    
    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Employee Projects</h1>
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
                                   href="#createMyModal">New Project</a>
                            </div>
                            <div class="col-lg-3">
                                <form class="form-horizontal" id ="project_report" method="POST" action="{{route('project.print')}}" >
                                    {{ csrf_field() }}
                                <div class="form-group-material row">
                                    <div class="col-sm-12 mb-12 ">
                                        <select name="project_id" id="all_projects" class="form-control ">
                                            <option value="" >Print Project Logged Hours</option>
                                         @php
                                             $all_projects= \App\ProjectTask::where('is_deleted',false)->get();
                                                 @endphp
                                            @foreach($all_projects as $project)
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
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('project.search')}}" >
                                    {{--{{ csrf_field() }}--}}
                                    <div class="row">
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input type='text' id='project_name' name="project_name"   value="{{\Request::get('project_name')?\Request::get('name'):''}}" class="input-material" />
            
                                                <label for="name" class="label-material" style="left: 17px">Project Name</label>
                                            </div>
                                            @if ($errors->has('project_name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('project_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        {{--<div class="form-group-material col-sm-3 "style="margin-top: 23px;">--}}
                                            {{--<div class='input-group-material'>--}}
                                                {{--<input type='text' id='name' name="name"   value="{{\Request::get('name')?\Request::get('name'):''}}" class="input-material" />--}}
                                                {{----}}
                                                {{--<label for="name" class="label-material" style="left: 17px">Employee Name </label>--}}
                                            {{--</div>--}}
                                            {{--@if ($errors->has('name'))--}}
                                                {{--<span class="help-block">--}}
                                            {{--<strong>{{ $errors->first('name') }}</strong>--}}
                                            {{--</span>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                        <div class="col-sm-1 " style="margin-top: 27px;">
                                            <button type="submit" class="btn btn-outline-success">Search Projects</button>
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
                                    <th>Project Title</th>
                                    <th>Sub Projects </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($projects)
                                    @foreach($projects as $project)
                                        
                                        <tr>
                                            <td>{{$project->project_name}}</td>
                                            <td>
                                                @php
                                                if ($project->sub_projects){
                                                $sub_projects=$project->sub_projects;
                                                }
                                                foreach ($sub_projects as $item){
                                                echo $item->name.', ';
                                                }
                                                @endphp
                                                
                                            </td>
                                            <td class="text-primary lead">
                                                <a  data-value="{{$project->id}}"  class="edit_link" href="#" >
                                                    <span class="fa fa-edit"></span>
                                                </a>
                                                <form class=form-inline" style="display: inline" method="POST"  action ="{{route('project.destroy',$project->id)}}"   enctype="multipart/form-data" >
                                                {{method_field('DELETE')}}
                                                {{ csrf_field()}}
                                                <button class="form-submit  fa fa-times" type="submit" > </button>
                                                </form>
                                                
                                               
                                            </td>
                                        
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bootstrap-iso">
                        {{$projects->links()}}
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
    <div id="createModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Create New Project</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{route('project.store')}}" >
                        {{ csrf_field() }}
                        
                        <div class="form-group-material">
                            <div class='  input-group-material ' >
                                <input autocomplete="off" type='text' id='project_title' name="project_title"   value="" class="input-material" />
                                
                                <label for="attendance" class="label-material">Project Title</label>
                            </div>
                            @if ($errors->has('project_title'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('project_title') }}</strong>
                                        </span>
                            @endif
                        </div>
                        <div class="bootstrap-iso">
                            <div class="form-group">
                                <label for="sub_projects" class="sub_projects">Sub Projects</label>
                                    <input type="text" value="" name="sub_projects" data-role="tagsinput" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit</button>
                        
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
    <div id="myModal" role="dialog" aria-labelledby="exampleModalLabels" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Update Project Title</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-bodys">
            
                </div>
        
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('scripts/bootstrap-tagsinput-angular.min.js')}}"></script>
    <script src="{{asset('scripts/bootstrap-tagsinput.min.js')}}"></script>

    <script type="text/javascript">

       $(".edit_link").on('click',function() {
           var project=$(this).data("value");

           $.get('/task/project/'+project+'/edit',function (data) {
               // $.each(data,alert(data);
               $('#modal-bodys').html(data);
               $('#myModal').modal();
               // $('#modal-body').change(
           });

       });
      
       $("#all_projects").change(function() {
           $('#project_report').submit();

       });
        $(".delete_link").on('click',function() {
            var project=$(this).data("value");
            $.get('/task/project/'+ project +'/',function (result) {
                $('#delete').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/project/'+result+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModals').modal();

            });
        });
    </script>
@endsection