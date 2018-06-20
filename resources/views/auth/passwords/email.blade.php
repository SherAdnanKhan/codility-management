@extends('layouts.without_nav')
@section('title')
    <title> {{config('app.name')}} | Email</title>
@endsection
@section('body')
    <div class="page login-page">
        <div class="container">
            <div class="form-outer text-center d-flex justify-content-center align-items-center" >
                <div class="form-inner flex-fill">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>Send Mail</p>
                        <form class="text-left form-validate align-items-center" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <div class="form-group-material">
                            <input id="email" type="email" class="input-material" name="email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                                <label for="email" class="label-material">E-Mail Address</label>
                        </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-success">
                                    Send Password Reset Link
                                </button>
                                <button type="reset" class="btn btn-outline-danger">
                                    Reset
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
