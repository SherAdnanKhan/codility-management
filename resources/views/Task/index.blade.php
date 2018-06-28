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
                                <a href="{{route('task.create')}}" class="btn btn-outline-success ">Add New Task
                                    <span class="fa fa-plus"></span></a>
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

                                    <th>Task Date</th>
                                    <th>Task Timing</th>
                                    <th>Task Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($tasks)
                                    @foreach($tasks as $task)
                                        <tr>

                                            <td>{{$task->created_at->toDateString()}}</td>
                                            <td>{{$task->time_take}}</td>
                                            <td>{{$task->description}}</td>
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
            var inform=$(this).data("value");
            $.get('/inform/'+ inform +'/',function (result) {
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