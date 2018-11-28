@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Capture Time Manage </title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">

@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Manage Screen Capture Duration</h1>
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
                                {{--<a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"--}}
                                   {{--href="#createMyModal">Add Category</a>--}}
                            </div>
                            <div class="col-lg-9 ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee Email</th>
                                    <th>Employee Screen Capture Duration</th>
                                    <th>Employee Imperative Minutes</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($users)
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->capture_duration}}</td>
                                            <td>{{$user->imperative_minutes?$user->imperative_minutes:''}}</td>
                                            <td><a  style="color:green "data-value="{{$user->id}}"  class="edit_link" href="#" >
                                                    <span class="fa fa-edit"></span>
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
                        {{$users->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="updateModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Manage Screen Capture Duration</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="update-modal-body">

                </div>

            </div>
        </div>
    </div>

@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        $(".edit_link").on('click',function() {
            var user = $(this).data("value");
            $.get('/screen/capture/' + user, function (data) {
                if (data.capture_duration != null){
                    var capture_duration = data.capture_duration

                }else {
                    var capture_duration ="0:00"
                }
                if (data.imperative_minutes !=null){
                    var imperative_minutes = data.imperative_minutes
                }else {
                    var imperative_minutes ="0:00"
                }
                $('.modal-body').html(' <div class="heading"> ' +
                    '<p >Employee Name :  ' + data.name + '</p>' +
                    '</div> <br/><br>' +
                    '<form class=form-inline" method="POST"  action ="/screen/capture/update/'+data.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('PATCH')}}' +
                    '{{ csrf_field()}}'+
                     ' <div class="form-group-material">' +
                    '                            <div class="input-group-material  date" >' +
                    '                                <input  type="text" id="time_capture_duration" value='+capture_duration+' name ="time_capture_duration" class="input-material" />' +
                    '                                <label for="time_capture_duration" class="label-material active">Screen Capture Duration</label>' +
                    '                            </div>' +
                    '                        </div>'+
                    ' <div class="form-group-material">' +
                    '                            <div class="input-group-material  date" >' +
                    '                                <input  type="text" id="imperative_minutes" value='+imperative_minutes+' name ="imperative_minutes" class="input-material" />' +
                    '                                <label for="imperative_minutes" class="label-material active">Imperative Minutes</label>' +
                    '                            </div>' +
                    '                        </div>'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Schedule </button> </form>');
                $(function () {
                    $('#time_capture_duration').datetimepicker({format: 'LT', format: 'H:mm',disabledHours: [9,10,11,12,13,14,15,16,17,18, 19, 20, 21, 22, 23, 24],defaultDate:new Date()});
                    $('#imperative_minutes').datetimepicker({format: 'LT', format: 'H',disabledHours: [4,5,6,7,8,9,10,11,12,13,14,15,16,17,18, 19, 20, 21, 22, 23, 24]});

                });
                $('#updateModal').modal();
            })
        });

    </script>

@endsection