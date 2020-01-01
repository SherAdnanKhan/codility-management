@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Admins</title>
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header>
            <h1 class="h3 display">Administrators List</h1>
        </header>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-9">
                                <a href="{{route('register.admin.form')}}" class="btn btn-outline-success ">Add New Administrator
                                    <span class="fa fa-plus"></span></a>
                            </div>
                          
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>

                                    <th>Administrator Name</th>
                                    <th>Admin Email</th>
                                    <th>Designation</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if($admins)
                                    @foreach($admins as $admin)
                                        <tr>

                                        <td>{{$admin->name}}</td>
                                        <td>{{$admin->email}}</td>
                                        <td>{{$admin->designation?$admin->designation: 'Administrator'}}</td>
                                        

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="bootstrap-iso">
                            {{$admins->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection