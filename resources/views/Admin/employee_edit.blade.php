@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Employee Detail</title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">

@endsection
@section('body')
    <div class="container" style="margin-top: 5%">
        <div class="">
            <div class="register-form" style="background-color: #fff">
                <div class="form-inner flex-fill ">
                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                    @if($user)
                        <form class="form-horizontal" method="POST" action= "{{ route('profile.update', $user->id) }}"  enctype="multipart/form-data" >
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="form-group-material">
                                 <input id="name" type="text" class="input-material" name="name" value="{{ $user->name }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <label for="name" class="label-material">Name</label>
                            </div>
                            @if(\Auth::user()->isAdmin())
                                <div class="form-group-material">
                                    <input id="email" type="email" class="input-material" name="email" value="{{ $user->email }}" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                <label for="email" class="label-material">E-Mail Address</label>
                                 </div>
                            @if ($user->isEmployee())
                                <div class="form-group-material">
                                    <div class='input-group-material date' >
                                        <input type='text' id='check_in_time' name="check_in_time"   value="{{$user->checkInTime}}" class="input-material" />
                                        <span class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </span>
                                        <label for="check_in_time" class="label-material">CheckIn Time</label>
                                    </div>
                                </div>
                                <div class="form-group-material">
                                    <div class='input-group-material date' >
                                        <input type='text' id='check_out_time' value="{{$user->checkOutTime}}" name="check_out_time" class="input-material" />
                                        <span class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </span>
                                        <label for="check_out_time" class="label-material">CheckOut Time</label>
                                    </div>
                                </div>
                                @endif
                                @elseif($user->isEmployee())

                                <div class="form-group-material">
                                    <input id="designation" type="text" class="input-material" name="designation" value="{{ $user->designation }}" required>
                                    @if ($errors->has('designation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('designation') }}</strong>
                                        </span>
                                    @endif
                                    <label for="email" class="label-material">Designation</label>
                                </div>
                                <div class="form-group-material">
                                    <input id="address" type="text" class="input-material" name="address" value="{{ $user->address }}" required autofocus>
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                <label for="address" class="label-material">Address</label>
                                 </div>
                                <div class="form-group-material">
                                    <input id="qualification" type="text" class="input-material" name="qualification" value="{{ $user->qualification }}" required autofocus>
                                    @if ($errors->has('qualification'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('qualification') }}</strong>
                                        </span>
                                    @endif
                                    <label for="qualification" class="label-material">Qualification</label>
                                </div>
                                <div class="form-group-material">
                                    <input id="contact" type="text" class="input-material" name="contact" value="{{ $user->phoneNumber }}" required autofocus>
                                    @if ($errors->has('contact'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                         </span>
                                    @endif
                                    <label for="phoneNumber" class="label-material">Phone Number</label>
                                </div>
                                    @endif
                                <div class="form-group-material">
                                    <input id="image" type="file" class="input-material" name="image" >
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            <div class="form-group-material">
                                <button type="submit" class="btn">
                                       Profile Update
                                </button>
                            </div>
                        </form>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#check_in_time').datetimepicker({format: 'LT', format: 'hh:mm A'});
            $('#check_out_time').datetimepicker({format: 'LT', format: 'hh:mm A' });
            $("#check_in_time").on("dp.change", function (e) {
                $('#check_out_time').data("DateTimePicker").minDate(e.date);
            });
            $("#check_out_time").on("dp.change", function (e) {
                $('#check_in_time').data("DateTimePicker").maxDate(e.date);
            });
        });
    </script>
@endsection