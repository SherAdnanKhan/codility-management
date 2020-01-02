@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Employee</title>
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header>
            <h1 class="h3 display">Employees List</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-9">
                                <a href="{{route('employees.create')}}" class="btn btn-outline-success ">Add New Employee
                                    <span class="fa fa-plus"></span></a>
                            </div>
                            <div class="col-lg-3">
                                <form action="{{route('employees.index')}}" method="GET">
                                    <div class="input-group input-group-md">
                                        <input class="form-control" placeholder="Search by Name" type="text" name="auto_complete_search" id ="auto_complete_search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-outline-success ">Search</button>
                                        </div>
                                    </div>
                                        <ul class="list-group" style="display: none;" id="name_listing">
                                        </ul>
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
                                    <th>Employee Email</th>
                                    <th>Designation</th>
                                    <th>Joining Date</th>
                                    <th>Address</th>
                                    <th>Qualification</th>
                                    <th>Phone Number</th>
                                    <th>Shift</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if($employees)
                                    @foreach($employees as $employee)
                                        <tr>
                                        <td>{{$employee->name}}</td>
                                        <td>{{$employee->email}}</td>
                                        <td>{{$employee->designation?$employee->designation: 'Administrator'}}</td>
                                        <td>{{$employee->joiningDate?$employee->joiningDate :'No Date'}}</td>
                                        <td>{{$employee->address?$employee->address:'Codility'}}</td>
                                        <td>{{$employee->qualification?$employee->qualification :''}}</td>
                                        <td>{{$employee->phoneNumber?$employee->phoneNumber:'Codility Number'}}</td>
                                        <td>{{$employee->shift_time != null?($employee->shift_time == 1?'Morning':'Evening' ):'Random'}}</td>

                                            <td class="text-primary lead">
                                            <a href="{{route('profile.edit',$employee->id)}}"><span class="fa fa-edit"></span></a>
                                            <form class="form-horizontal" method="POST" action = "{{ route('profile.destroy', $employee->id) }}"  enctype="multipart/form-data" >
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button class="form-submit fa fa-times" type="submit" >
                                                </button>
                                            </form>
                                        </td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="bootstrap-iso">
                            {{$employees->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection