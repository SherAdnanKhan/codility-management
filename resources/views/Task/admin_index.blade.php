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
                        <div class="row">
                            <div class="col-lg-9">

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
                                    <th>Employee Name</th>
                                    <th>Task Date</th>
                                    <th>Task Created</th>
                                    <th>Task Timing</th>
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
                                            <td>{{substr($task->description,0,50 )."..."}}</td>
                                            <td><a href="{{route('task.edit',$task->id)}}"> <span class="fa fa-edit"></span>
                                                </a>
                                                <a href="{{route('task.show',$task->id)}}"> <span class="fa fa-file"></span>
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

    <script>
        $(".delete_link").on('click',function() {
            var task=$(this).data("value");
            $.get('/delete-task/'+ task +'/',function (result) {
                $('#modal-body').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/task/'+result.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModal').modal();
                console.log(result);

            })

        });
    </script>
@endsection