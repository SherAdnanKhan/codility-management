@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} |  Profile</title>
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
                        <form class="form-horizontal" id="employee" method="POST" action= "{{ route('profile.update', $user->id) }}"  enctype="multipart/form-data" >
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="form-group-material">
                                 <input autocomplete="off" id="name" type="text" class="input-material" name="name" value="{{ $user->name }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <label for="name" class="label-material">Name</label>
                            </div>
                            @if(\Auth::user()->isAdmin())
                                <div class="form-group-material">
                                    <input autocomplete="off" id="email" type="email" class="input-material" name="email" value="{{ $user->email }}" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                <label for="email" class="label-material">E-Mail Address</label>
                                 </div>
                                <div class="form-group-material">
                                 <input autocomplete="off" id="cnic" type="text"  class="input-material" name="cnic" value="{{ $user->cnic_no }} " required>
            
                                    @if ($errors->has('cnic'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('cnic') }}</strong>
                                    </span>
                                    @endif
                                    <label for="cnic" class="label-material">CNIC No</label>
                                </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="compensatory_leaves" type="number"  class="input-material" name="compensatory_leaves" value="{{ $user->compensatory_leaves }}" >
            
                                    @if ($errors->has('compensatory_leaves'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('compensatory_leaves') }}</strong>
                                    </span>
                                    @endif
                                    <label for="cnic" class="label-material">Compensatory Leaves (Optional)</label>
                                </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="ntn" type="text"  class="input-material" name="ntn" value="{{ $user->ntn_no }}" >
            
                                    @if ($errors->has('ntn'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('ntn') }}</strong>
                                    </span>
                                    @endif
                                    <label for="ntn" class="label-material">NTN No</label>
                                </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="account_no" type="text"  class="input-material" name="account_no" value="{{ $user->bank_account_no }}" >
            
                                    @if ($errors->has('account_no'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('account') }}</strong>
                                    </span>
                                    @endif
                                    <label for="account_no" class="label-material">Bank Account No</label>
                                </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="blood_group" type="text"  class="input-material" name="blood_group" value="{{ $user->blood_group }}">
            
                                    @if ($errors->has('blood_group'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('blood_group') }}</strong>
                                    </span>
                                    @endif
                                    <label for="blood_group" class="label-material">Blood Group</label>
                                </div>
                            @if ($user->isEmployee())

                                <div class="form-group-material">
                                    <div class='input-group-material date' >
                                        <input autocomplete="off" type='text' id='check_in_time' name="check_in_time"   value="{{$user->checkInTime}}" class="input-material" />
                                        <span class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </span>
                                        <label for="check_in_time" class="label-material">CheckIn Time</label>
                                    </div>
                                </div>
                                <div class="form-group-material">
                                    <div class='input-group-material date' >
                                        <input autocomplete="off" type='text' id='check_out_time' value="{{$user->checkOutTime}}" name="check_out_time" class="input-material" />
                                        <span class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </span>
                                        <label for="check_out_time" class="label-material">CheckOut Time</label>
                                    </div>
                                </div>
                                    <div class="form-group-material">
                                        <div class='input-group-material date' >
                                            <input autocomplete="off" type='text' id='non_working_hour'  name ="non_working_hour" class="input-material" value="{{\Carbon\Carbon::createFromTimestamp($user->breakAllowed)->format('H:i')}}"/>
                                            @if ($errors->has('non_working_hour'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('non_working_hour') }}</strong>
                                    </span>
                                            @endif
                                            <label for="non_working_hour" class="label-material">Break Interval</label>
                                        </div>
                                    </div>
                                    <div class="form-group-material">
                                        <div class='input-group-material ' >
                                            <input autocomplete="off" type='number' id='workingDays'  name ="workingDays" class="input-material" value="{{$user->workingDays}}"/>
                                            @if ($errors->has('workingDays'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('workingDays') }}</strong>
                                    </span>
                                            @endif
                                            <label for="non_working_hour" class="label-material">Working Days</label>
                                        </div>
                                    </div>
                                    <div class="form-group-material">

                                        <input autocomplete="off" type='number' id="opening_balance" name="opening_balance"   value="{{$user->opening_balance?$user->opening_balance:''}}" class="input-material" />
                                        @if ($errors->has('opening_balance'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('opening_balance') }}</strong>
                                    </span>
                                        @endif
                                        <label for="name" class="label-material">Opening Balance</label>
                                    </div>
                                    <div class="form-group row of-button" >
                                        <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Has Left</label>

                                        <label class="switch" class="col-sm-offset-3 ">
                                            <input type="checkbox" name="abended" {{$user->abended?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="form-group row of-button" >
                                        <label for="is_hr" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Assign Rights (HR)</label>
                
                                        <label class="switch" class="col-sm-offset-3 ">
                                            <input type="checkbox" name="is_hr" {{$user->is_hr?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                @endif
                                @elseif($user->isEmployee())

                                <div class="form-group-material">
                                    <input autocomplete="off" id="designation" type="text" class="input-material" name="designation" value="{{ $user->designation }}" required>
                                    @if ($errors->has('designation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('designation') }}</strong>
                                        </span>
                                    @endif
                                    <label for="email" class="label-material">Designation</label>
                                </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="address" type="text" class="input-material" name="address" value="{{ $user->address }}" required autofocus>
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                <label for="address" class="label-material">Address</label>
                                 </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="qualification" type="text" class="input-material" name="qualification" value="{{ $user->qualification }}" required autofocus>
                                    @if ($errors->has('qualification'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('qualification') }}</strong>
                                        </span>
                                    @endif
                                    <label for="qualification" class="label-material">Qualification</label>
                                </div>
                                <div class="form-group-material">
                                    <input autocomplete="off" id="contact" type="text" class="input-material" name="contact" value="{{ $user->phoneNumber }}" required autofocus>
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
                                <button type="submit" class="btn btn-outline-success">
                                       Profile Update
                                </button>
                                <button type="button" id="button_clear" class="btn btn-outline-danger">
                                    Reset
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
        $('#non_working_hour').datetimepicker({format:'H:mm'})
        $('#button_clear').click(function(){
            $('#employee input[type="text"]').val('');
            $('#employee input[type="email"]').val('');
        });

    </script>
@endsection
