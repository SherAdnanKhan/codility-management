@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Manage Leaves</title>
@endsection
@section('body')
    @if (session('timetable'))
        <div class="alert alert-success">
            {{ session('timetable') }}
        </div>
    @endif
        <div class="container" style="margin-top: 6%;">
            <div class="row">
    <div class="col-lg-6" >
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4>Add Leave</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{route('leave.store')}}">
                    {{ csrf_field() }}
                        {{--@foreach($timetable as $time)--}}
                            <div class="form-group-material">
                            <div class='input-group-material ' >
                                <input type='text' id="name" name="name"   value="" class="input-material" />
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <label for="name" class="label-material">Leave Type</label>
                            </div>
                        </div>
                        <div class="form-group-material">

                                <input type='number' id="allowed" name="allowed"   value="" class="input-material" />
                            @if ($errors->has('allowed'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('allowed') }}</strong>
                                    </span>
                            @endif
                                <label for="name" class="label-material">Leave Allowed</label>
                        </div>
                        <div class="form-group-material">
                            <label for="color" class="label-material">Color</label>
                            <input type="color" name="color_code" class=" col-2" value="#ffffff"/>
                            @if ($errors->has('color_code'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('color_code') }}</strong>
                                    </span>
                            @endif
                        </div>

                        {{--@endforeach--}}
                        <button type="submit" class="btn btn-outline-success">Add Leaves</button>

                    </form>

                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6" >
    <div class="card">
        <div class="card-header">
            Leaves Types
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Color</th>
                        <th>Leave Type</th>
                        <th>Days Allowed</th>
                        <th>Edit</th>
                        <th>Delete</th>

                    </tr>
                    </thead>
                    <tbody>
                    @if($leaves)
                        @foreach($leaves as $leave)
                    <tr>
                        <td style="background-color: {{$leave->color_code?$leave->color_code:'yellow'}}"></td>
                        <td>{{$leave->name}}</td>
                        <td>{{$leave->allowed}}</td>
                        <td>
                            <a  style="color: {{$leave->color_code?$leave->color_code:'yellow'}}" data-value="{{$leave->id}}"  class="edit_link" href="#" >

                                <span class="fa fa-edit"></span>
                            </a>
                        </td>
                        <td >
                            <form class="form-horizontal" method="POST" action = "{{ route('leave.destroy', $leave->id) }}" >
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" style="background-color: {{$leave->color_code?$leave->color_code:'yellow'}}" >
                                    <span class="fa fa-times"></span>
                                </button>
                            </form>
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
    <div id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Update Leaves</h5>
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

        $(".edit_link").on('click',function() {
            var category=$(this).data("value");

            $.get('/leave/'+category+'/edit',function (data) {
                // $.each(data,function (index,state) {
                $('#modal-body').html(data);
                $('#myModal').modal();
                console.log(data);

            })

        });
    </script>
@endsection