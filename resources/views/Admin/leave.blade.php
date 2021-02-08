@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Manage Leaves</title>
@endsection
@section('page_styles')
    
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')
    @if (session('status_error'))
        <div class="alert alert-success">
            {{ session('status_error') }}
        </div>
    @endif
        <div class="container" style="margin-top: 6%;">
            <div class="row">
    <div class="col-lg-6" >
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4>Add Leave</h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{route('leave.store')}}">
                    {{ csrf_field() }}
                        {{--@foreach($timetable as $time)--}}
                            <div class="form-group-material">
                            <div class='input-group-material ' >
                                <input autocomplete="off" type='text' id="name" name="name"   value="" class="input-material" />
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <label for="name" class="label-material">Leave Type</label>
                            </div>
                        </div>
                        <div class="form-group-material">

                                <input type='number' id="allowed" name="allowed"   value="" class="input-material" />
                            @if ($errors->has('allowed'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('allowed') }}</strong>
                                    </span>
                            @endif
                                <label for="name" class="label-material">Leave Allowed</label>
                        </div>
                        <div class="form-group row of-button" >
                            <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label " >Is Public Holiday</label>
                            <label class="switch" class="col-sm-offset-3 " style="position: absolute ;margin-left: 79%;margin-top: -2%;">
                                <input type="checkbox" name="public_holiday" id="public_holiday">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        
                        <div class="form-group-material">
                            <label for="color" class="label-material">Color</label>
                            <input type="color" name="color_code"  class=" col-2" value="#ffffff"/>
                            @if ($errors->has('color_code'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('color_code') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="date_of_holiday"></div>
                        {{--@endforeach--}}
                        <button type="submit" class="btn btn-outline-success">Add Leaves</button>
                        <button type="reset" class="btn btn-outline-danger">Reset</button>
                    </form>

                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6" >
    <div class="card">
        <div class="card-header">
            Leaves Types
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Color</th>
                        <th>Leave Type</th>
                        <th>Days Allowed</th>
                        <th>Public Holiday</th>
                        <th>Edit</th>
                        {{--<th>Delete</th>--}}

                    </tr>
                    </thead>
                    <tbody>
                    @if($leaves)
                        @foreach($leaves as $leave)
                    <tr>
                        <td style="background-color: {{$leave->color_code?$leave->color_code:'yellow'}}"></td>
                        <td>{{$leave->name}}</td>
                        <td>{{$leave->allowed}}</td>
                        <td>{{$leave->public_holiday == true ? 'Yes':'No'}}</td>

                        <td>
                            <a style="color: {{$leave->color_code?$leave->color_code:'yellow'}}" data-value="{{$leave->id}}" class="edit_link" href="javascript://">
                                <span class="fa fa-edit"></span>
                            </a>

                        </td>
                        {{--<td >--}}
                            {{--<form class="form-horizontal" method="POST" action = "{{ route('leave.destroy', $leave->id) }}" >--}}
                                {{--{{ method_field('DELETE') }}--}}
                                {{--{{ csrf_field() }}--}}
                                {{--<button type="submit" class="form-submit fa fa-times delete_link" style="background-color: {{$leave->color_code?$leave->color_code:'yellow'}}" >--}}

                                {{--</button>--}}
                            {{--</form>--}}
                        {{--</td>--}}

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
    <div id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Update Leaves</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">

                </div>

            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    
    
    <script src="{{asset('scripts/moment.js')}}"></script>
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>

    <script>
        /*
        function test(v){
            var this_data = v.getAttribute("data-value");
            alert( this_data );
            //alert('asdf');
        };
        */
        $(document).ready(function () {
            var ckbox = $('#public_holiday');
        
            $('input').on('click',function () {
                if (ckbox.is(':checked')) {
                    
                    $('.date_of_holiday').html("<div class='form-group-material'>" +
                        "                            <div class=' bootstrap-iso input-group-material date' >" +
                        "                                <input  type='text' id='date' name='date' class='input-material' />" +
                        "\n" +
                        "                                <label for='date' class='label-material active '>Date Of Public Holiday</label>" +
                        "                            </div>" +
                        "@if($errors->has('date'))" +
                        "                                <span class='help-block'>" +
                        "                                        <strong>{{$errors->first('date') }}</strong>" +
                        "                                    </span>" +
                        "                            @endif" +
                        "                        </div>");
        
        
                    $(function () {
                        $('#date').datetimepicker({
                            format:'l'
                        });
                    });
                } else {
                    $('.date_of_holiday').html(" ")
                }
            });
        });
        
    </script>
    <script type="text/javascript">
        
        $(".edit_link").on('click',function() {
            var category=$(this).data("value");

            $.get('/leave/'+category+'/edit',function (data) {
                // $.each(data,function (index,state) {
                $('#modal-body').html(data);
                $('#myModal').modal();
                // $('#modal-body').change(
                  $( function() {
                        $('input').on('click',function () {
                            var ckbox = $('#public_holidays');

                            if (ckbox.is(':checked')) {

                                $('.date_of_holidays').html("<div class='form-group-material'>" +
                                    "                            <div class=' bootstrap-iso input-group-material date' >" +
                                    "                                <input autocomplete='off' type='text' id='date' name='date' class='input-material' />" +
                                    "\n" +
                                    "                                <label for='date' class='label-material active'>Date Of Public Holiday</label>" +
                                    "                            </div>" +
                                    "@if($errors->has('date'))" +
                                    "                                <span class='help-block'>" +
                                    "                                        <strong>{{$errors->first('date') }}</strong>" +
                                    "                                    </span>" +
                                    "                            @endif" +
                                    "                        </div>");


                                $(function () {
                                    $('#date').datetimepicker({
                                        format:'l'
                                    });
                                });
                            } else {
                                $('.date_of_holidays').html(" ")
                            }
                        }); // ?
                    });
            });

        });
        
    </script>

@endsection