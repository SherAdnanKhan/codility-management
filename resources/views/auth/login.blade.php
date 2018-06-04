@extends('layouts.without_nav')

@section('body')
    <div class="page login-page">
        <div class="container">
            <div class="form-outer text-center d-flex justify-content-center align-items-center" >
                <div class="form-inner flex-fill">
                    <div class="logo"> <img style="max-width: 70%" class="img-responsive " src="{{asset('images/logo.png')}}" alt="Codility Management"></div>
                    <form  class="text-left form-validate align-items-center" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group-material ">
                            <input id="email" type="email" value="{{ old('email') }}"  name="email" required data-msg="Please enter your Email" class="input-material">
                            <label for="email" class="label-material">Email</label>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group-material">
                            <input id="password" type="password" name="password"  required data-msg="Please enter your password" class="input-material">
                            <label for="password" class="label-material">Password</label>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group-material">
                            <div class="checkbox">
                                <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me</label>
                            </div>
                        </div>
                        <div class="form-group-material">
                            <button type="submit" class="btn">
                                Login
                            </button>
                            <a class="btn btn-link" href="{{ route('password.request') }}">Forgot Your Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


