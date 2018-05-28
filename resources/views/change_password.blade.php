@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Change Password</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('change.password') }}">
                            {{ csrf_field() }}

                            @if(isset($request))
                            <input  class="disabled " style="display:none"  type="hidden" id="check" name="check" value="{{$request->email}} ">
                            @endif

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">New Password</label>

                                <div class="col-md-6">
                                    <input id="new_password" type="password" class="form-control" name="new_password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                       New Password
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
