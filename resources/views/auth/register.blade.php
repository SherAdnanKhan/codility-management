@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Register Employee</title>
@endsection
@section('body')
    <div class="container" style="margin-top: 5%">
        <div class="">
            <div class="register-form" style="background-color: #fff">
                <div class="form-inner flex-fill ">
                    <div class="card-header">
                        <h4>Employee Register</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" id="register" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="form-group-material">
                                <input autocomplete="off" id="name" type="text"  class="input-material" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <label for="name" class="label-material">Name</label>
                            </div>
                            <div class="form-group-material">
                                <input autocomplete="off" id="email" type="email"  class="input-material" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                <label for="email" class="label-material">E-Mail Address</label>
                            </div>
                            <div class="form-group-material">
                                <input autocomplete="off" id="designation" type="text"  class="input-material" name="designation" value="{{ old('designation') }}" required>
                                @if ($errors->has('designation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('designation') }}</strong>
                                    </span>
                                @endif
                                <label for="email" class="label-material ">Designation</label>
                            </div>
                            <div class="form-group-material">
                                <input autocomplete="off" id="joiningDate" type="date"  class="input-material" name="joiningDate" value="{{ old('joiningDate') }}" required autofocus>
                                @if ($errors->has('joiningDate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('joiningDate') }}</strong>
                                    </span>
                                @endif
                                <label for="joiningDate" class="label-material active">Joining Date</label>
                            </div>
                            <div class="form-group-material">
                                <input id="password" type="password"  class="input-material" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <label for="password" class="label-material">Password</label>
                            </div>
                            <div class="form-group-material">
                                <input id="confirm_password" type="password"  class="input-material" name="confirm_password" required>
                                @if ($errors->has('confirm_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                                @endif
                                <label for="confirm_password" class="label-material">Confirm Password</label>
                            </div>
                            <div class="form-group-material">
                                <input autocomplete="off" id="address" type="text"  class="input-material" name="address" value="{{ old('address') }}" required autofocus>
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                                <label for="address" class="label-material">Address</label>
                            </div>
                            <div class="form-group-material">
                                <input autocomplete="off" id="qualification" type="text"  class="input-material" name="qualification" value="{{ old('qualification') }}" required autofocus>
                                @if ($errors->has('qualification'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('qualification') }}</strong>
                                    </span>
                                @endif
                                <label for="qualification" class="label-material">Qualification</label>
                            </div>
                            <div class="form-group-material">
                                <input autocomplete="off" id="phoneNumber" type="text"  class="input-material" name="phoneNumber" value="{{ old('phoneNumber') }}" required autofocus>
                                @if ($errors->has('phoneNumber'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phoneNumber') }}</strong>
                                    </span>
                                @endif
                                <label for="phoneNumber" class="label-material">Phone Number</label>
                            </div>
                            <div class="form-group-material">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-outline-success">
                                        Register
                                    </button>
                                    <button type="button" id="button_clear" class="btn btn-outline-danger">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script type="text/javascript">
        $('#button_clear').click(function(){
            $('#register input[type="text"]').val('');
            $('#register input[type="email"]').val('');
            $('#register input[type="password"]').val('');
        });
    </script>
@endsection