@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Employee</title>
@endsection
@section('body')

    <div class="container-fluid">
        <!-- Page Header-->
        <header>
            <h1 class="h3 display">Employee Detail</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-9">
                                <a href="{{route('register')}}" class="btn btn-outline-success ">Add New Employee
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

                                    <th>Employee Name</th>
                                    <th>Employee Email</th>
                                    <th>Designation</th>
                                    <th>Joining Date</th>
                                    <th>Address</th>
                                    <th>Qualification</th>
                                    <th>Phone Number</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($users)
                                    @foreach($users as $user)
                                        <tr>

                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->designation?$user->designation: 'Administrator'}}</td>
                                        <td>{{$user->joiningDate?$user->joiningDate :'No Date'}}</td>
                                        <td>{{$user->address?$user->address:'Codility'}}</td>
                                        <td>{{$user->qualification?$user->qualification :''}}</td>
                                        <td>{{$user->phoneNumber?$user->phoneNumber:'Codility Number'}}</td>
                                        <td class="text-primary lead"><a href="{{route('profile.edit',$user->id)}}"><span class="fa fa-edit"></span></a>
                                            <form class="form-horizontal" method="POST" action = "{{ route('profile.destroy', $user->id) }}"  enctype="multipart/form-data" >
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="fa fa-times">
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
    @endsection