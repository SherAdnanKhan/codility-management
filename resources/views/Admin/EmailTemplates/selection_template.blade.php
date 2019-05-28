
@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Email Templates</title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-tagsinput.css')}}">
    <style>
        .gradiant_custom {
            background: #212529;
            box-shadow: 10px 10px 5px 0px rgba(115,107,115,1);
            margin-right: 30px;
            margin-left: 50px;
            padding: 20px;
            margin-top: 20px;

        }
        b{
            font-size: 20px;
        }
        .send_button{
            background: transparent;
            border: transparent;
            color: white;
            margin: 15px 0 0 0;
            float: right;
        }
    </style>
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
                        <h4>Email Templates </h4>
                        @if(isset($email))
                            <p>You are sending email To <b>{{$email}}</b> From <b>{{config('mail.from.address')}}</b></p>
                        @endif
                    </div>
                    @if(isset($email_templates))

                        {{--<form  action="{{route('email_template.send_email')}}" method="POST" enctype="multipart/form-data">--}}

                        <div class="card-body">
                            <h5>Variables for adding data in Email Body</h5>
                            <p style=""> <i>1:</i> <b>@</b> Date-Month-Year <b>@</b> for adding an Interview date </p> <p> <i>2:</i> <b>*</b> Hours:Minutes am/pm <b>*</b> for adding an Interview time</p>
                            <p style=""> <i>3:</i> <b>#</b> For Bold Text <b>#</b> for adding an bold text in email </p>
                            {{--<p style=""> <i>4:</i> <b>%</b> Company Name <b>%</b> for adding an Company Name </p>--}}
                            {{--<p style=""> <i>5:</i> <b>$</b> Address <b>$</b> for adding an Address </p>--}}
                            <div class="row">
                                {{csrf_field()}}
                                @foreach($email_templates as $email_template)
                                    <div class="col-md-5 gradiant_custom">
                                        <form  action="{{route('email_template.send_email',$email_template->id)}}" method="POST" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            {{ method_field('PATCH') }}
                                            <input type="hidden" value="{{$email}}" name="email">
                                            <textarea class="form-control"   cols="40" rows="2" name="email_header"  style="background-color: #ffffff !important;">{{$email_template->header}}</textarea>
                                            <br>
                                            <textarea class="form-control"   cols="40" rows="4"  name="email_body" style="background-color: #ffffff !important;">{{$email_template->body}}</textarea>
                                            <button type="submit" class="fa fa-paper-plane send_button"> Send Email</button>
                                        </form>
                                    </div>

                                @endforeach
                            </div>
                        </div>
                        {{--</form>--}}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="edit" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTiltled" class="modal-title">Update Email Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modalForm" action="{{route('email_template.send_customized_email')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button id="submitBtn" type="submit" class="btn btn-outline-success ">Send Email</button>
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
        $(".applicant_id").on('click',function() {
            var email_template = $(this).data('value');
            $.get('/email_template/'+email_template+'/',function (data) {
                $('.modal-body').html('<div class="card"> <div class="card-body">' +
                    '<div class="form-group row"> <label for="reason" class="select-label  form-control-label ">Email Subject</label> ' +
                    '<div class="col-sm-12  mb-12 "><textarea class="form-control"   name="email_header" cols="40" rows="2">'+data.header+' </textarea> </div></div>' +
                    '<div class="form-group row"> <label for="reason" class="select-label  form-control-label ">Email Body</label>' +
                    '<div class="col-sm-12  mb-12 "><textarea class="form-control"  name="email_body" cols="38" rows="2">'+data.body+' </textarea> </div></div></div></div>');
                $('#edit').modal();
            });
        });
    </script>
@endsection
