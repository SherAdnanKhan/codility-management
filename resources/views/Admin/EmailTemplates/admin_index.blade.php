@extends('layouts.app')
@section('title')
<title> {{config('app.name')}} | Manage Leaves</title>
@endsection
@section('page_styles')
    
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-tagsinput.css')}}">
@endsection
@section('body')
@if (session('status_error'))
<div class="alert alert-success">
    {{ session('status_error') }}
</div>
@endif
<div class="container" style="margin-top: 6%;">
    <div class="row">
       
        <div class="col-lg-12" >
            <div class="card">
                <div class="card-header">
                    <a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success"
                       id="MainNavHelp"
                       href="#createModal">Add Email Templates </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Email Subject</th>
                                <th>Email Header</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($email_templates)
                            @foreach($email_templates as $email_template)
                            <tr>
                                <td><textarea class="form-control" cols="40" disabled="disabled">{{$email_template->header?$email_template->header:'System`s Default Subject'}}</textarea></td>
                                <td><textarea class="form-control" cols="40" disabled="disabled">{{$email_template->body}}</textarea></td>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="createModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5  class="modal-title">Add Email Template</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="modal-body">
                <form  action="{{route('email_template.store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group-material">
                            <label for="email_subject" class="select-label form-control-label ">Email`s Subject</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="email_subject" class="form-control">{{old('email_subject')}}</textarea>
                            </div>
                            @if ($errors->has('email_subject'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email_subject') }}</strong>
                                    </span>
                            @endif
                        </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group-material">
                            <label for="email_body" class="select-label form-control-label ">Email`s Body</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="email_body" class="form-control">{{old('email_body')}}</textarea>
                            </div>
                            @if ($errors->has('email_body'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email_body') }}</strong>
                                    </span>
                            @endif
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submitBtn" type="submit" class="btn btn-outline-success ">Submit</button>
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
