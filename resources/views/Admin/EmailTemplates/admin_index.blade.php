@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Manage Email Templates</title>
@endsection
@section('page_styles')

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
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
                        <h5>Variables for adding data in Email Body</h5>
                        <p style=""> <i>1:</i> <b>@</b> Date-Month-Year <b>@</b> for adding an Interview date </p> <p> <i>2:</i> <b>*</b> Hours:Minutes am/pm <b>*</b> for adding an Interview time</p>
                        <p style=""> <i>3:</i> <b>#</b> For Bold Text <b>#</b> for adding an bold text in email </p>
                        {{--<p style=""> <i>4:</i> <b>%</b> Company Name <b>%</b> for adding an Company Name </p>--}}
                        {{--<p style=""> <i>5:</i> <b>$</b> Address <b>$</b> for adding an Address </p>--}}
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Email Subject</th>
                                    <th>Email Header</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($email_templates)
                                    @foreach($email_templates as $email_template)
                                        <tr>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$email_template->header?$email_template->header:'System`s Default Subject'}}</textarea></td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$email_template->body}}</textarea></td>
                                            <td><a title="Update Email Template"  href='#' data-value='{{$email_template->id}}' class="email_template"><i class="fa fa-edit"></i></a>
                                                <a  data-value="{{$email_template->id}}"  class="delete_link" href="#" >
                                                    <span class="fa fa-times"></span>
                                                </a>
                                            </td>
                                        </tr>
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
    <div id="edit" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTiltled" class="modal-title">Update Email Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-bodys">
                </div>
            </div>
        </div>
    </div>
    <div id="deleteMyModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DELETE Email Template</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-bodyss">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
        $(".email_template").on('click',function() {
            var email_template = $(this).data('value');
            $.get('/email_template/'+email_template+'/',function (data) {
                $('.modal-bodys').html('<div class="modal-body"> <form id="modalForm" action="/email_templates/update/'+data.id+'" method="POST" >'+
                    '{{method_field("PATCH")}} {{csrf_field()}}'+
                    '<div class="card"> <div class="card-body">' +
                    '<div class="form-group row"> <label for="reason" class="select-label  form-control-label ">Email Subject</label> ' +
                    '<div class="col-sm-12  mb-12 "><textarea class="form-control"   name="email_header" cols="40" rows="2">'+data.header+' </textarea> </div></div>' +
                    '<div class="form-group row"> <label for="reason" class="select-label  form-control-label ">Email Body</label>' +
                    '<div class="col-sm-12  mb-12 "><textarea class="form-control"  name="email_body" cols="40" rows="2">'+data.body+' </textarea> </div></div></div>'+
                    '<div class="modal-footer">'+
                    '<button id="submitBtn" type="submit" class="btn btn-outline-success ">Update</button>'+
                    '<button type="button" class="btn " data-dismiss="modal">Close</button>'+
                    '</div></form></div>');
                $('#edit').modal();
            });
        });
        $(".delete_link").on('click',function() {
            var email_template=$(this).data("value");
            $.get('/email_template/'+ email_template +'/',function (result) {
                $('#modal-bodyss').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/email_template/'+result.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModal').modal();
                console.log(result);
            })
        });
    </script>
@endsection