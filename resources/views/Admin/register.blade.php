@extends('layouts.app')

@section('body')
    <div class="container" style="margin-top: 5%;">
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4>Admin Register</h4>
                </div>
                <div class="card-body">
                        <form class="form-horizontal" method="POST" action="{{ route('admin.register') }}">
                            {{ csrf_field() }}
                            <div class="form-group-material">
                                <input id="name" type="text" class="input-material" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                    <label for="name" class="label-material">Name</label>
                            </div>
                            <div class="form-group-material">
                                <input id="email" type="email" class="input-material" name="email" value="{{ old('email') }}" required>
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
                                <input id="confirm_password" type="password" class="input-material" name="confirm_password" required>
                                @if ($errors->has('confirm_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                                @endif
                                    <label for="password-confirm" class="label-material">Confirm Password</label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn ">
                                        Register
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
