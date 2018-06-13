@extends('layouts.without_nav')
@section('title')
    <title> {{config('app.name')}} |Change Password </title>
@endsection
@section('body')
    <div class="container">
        <div class="page login-page">
            <div class="container">
                <div class="form-outer d-flex justify-content-center align-items-center">
                    <div class="form-inner flex-fill">
                        <div class="card-header">
                            <p class="text-dark">Hi, {{\Auth::user()->name}}</p>
                            @if($user)
                                <h4 class="pull-right ">Change Password</h4>
                        </div>
                        <form class= "text-left form-validate align-items-center" method="POST" action="{{ route('new.password') }}">
                            {{ csrf_field() }}
                            <div class="form-group-material">
                                <input id="new_password" type="password" class="form-control" name="new_password"  required data-msg="Password is incorrect" class="input-material" >
                                <label for="new_password" class =" control-label-material ">New Password</label>
                            </div>
                            @if ($errors->has('new_password'))
                                <span class="help-block">
                                        <strong class="alert-danger ">{{ $errors->first('new_password') }}</strong>
                                </span>
                            @endif
                            <div class="form-group-material">
                                <button type="submit" class="btn ">
                                    New Password
                                </button>
                                <button type="reset" class="btn btn-outline-danger">Reset</button>
                            </div>
                        </form>
                        @else
                            You are not a new Employee
                        @endif
                    </div>
                </div>
            </div>
        </div>
@endsection
