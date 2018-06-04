@extends('layouts.without_nav')

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
                    <p>Reset Password</p>
                        <form class="text-left form-validate align-items-center" method="POST" action="{{ route('reset') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group-material">
                                <input id="email" type="email" class="input-material" name="email" value="{{ $email or old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                <label for="email" class="label-material">E-Mail Address</label>
                            </div>
                            <div class="form-group-material">
                                <input id="password" type="password" class="input-material" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                <label for="password" class="label-material">Password</label>
                            </div>
                            <div class="form-group-material">
                                 <input id="password-confirm" type="password" class="input-material" name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                                <label for="password-confirm" class="label-material">Confirm Password</label>
                            </div>
                            <div class="form-group-material">
                                <button type="submit" class="btn">
                                        Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
