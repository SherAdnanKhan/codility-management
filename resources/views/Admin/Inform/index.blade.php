@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Inform</title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Informs Employee Detail</h1>
        </header>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-lg-3">
                                <a href="{{route('inform.create')}}" class="btn btn-outline-success ">Add Employee Inform
                                    <span class="fa fa-plus"></span></a>
                            </div>
                            <div class=" col-lg-9">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 ">
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('inform.search')}}" >
                                    {{--{{ csrf_field() }}--}}
                                    <div class="row">
                                        <div class="form-group-material col-sm-2 ">
                                            <label for="inform_type" class="select-label form-control-label ">Search Inform By</label>
                                            <select name="filter" id="filter" class="form-control filters ">
                                                <option>Please Choose</option>

                                                <option {{\Request::get('filter')=='today'?'selected ':''}}value="today">Today</option>
                                                <option {{\Request::get('filter')=='week'?'selected ':''}}value="week">This Week</option>
                                                <option {{\Request::get('filter')=='month'?'selected ':''}}value="month">This Month</option>
                                                <option {{\Request::get('filter')=='year'?'selected ':''}}value="year">This Year</option>
                                                <option {{\Request::get('filter')=='custom'?'selected ':''}}value="custom">Custom</option>

                                            </select>

                                            @if ($errors->has('filter'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('filter') }}</strong>
                                    </span>
                                            @endif
                                        </div>

                                        <div class="form-group-material date_search col-sm-2" style="margin-top: 23px;">

                                            <div class='bootstrap-iso input-group-material' >
                                                <input autocomplete="off" type='text' id='start_date' name="start_date" value="{{old('start_date')}}" class="input-material" />

                                                <label for="start_date" style="left: 17px" class="label-material">Start Date Form</label>
                                            </div>
                                            @if ($errors->has('start_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                            @endif

                                        </div>
                                        <div class="form-group-material date_search col-sm-2" style="margin-top: 23px;">

                                            <div class=' bootstrap-iso input-group-material date' >
                                                <input autocomplete="off" type='text' id='end_date' name="end_date" value="{{old('end_date')}}" class="input-material" />

                                                <label for="end_date" style="left: 17px" class="label-material">End Date Form</label>
                                            </div>
                                            @if ($errors->has('end_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                            @endif

                                        </div>

                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='name' name="name"   value="{{\Request::get('name')?\Request::get('name'):''}}" class="input-material" />

                                                <label for="name" class="label-material" style="left: 17px">Employee Name (Optional)</label>
                                            </div>
                                            @if ($errors->has('name'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-1 " style="margin-top: 27px;">
                                            <button type="submit" class="btn btn-outline-success">Search Inform</button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>

                                    <th>Employee Name</th>
                                    <th>Attendance Date</th>
                                    <th>Informed At</th>
                                    <th>Inform Type</th>
                                    <th>Informed Status</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($informs)
                                    @foreach($informs as $inform)
                                        <tr>

                                            <td>{{$inform->users->name}}</td>
                                            <td>{{$inform->attendance_date}}</td>
                                            <td>{{$inform->inform_at}}</td>
                                            <td>{{$inform->inform_type}}{{$inform->leaves?' ON '.$inform->leaves->name:''}}</td>
                                            <td>{{$inform->inform_late?'Yes Late Informed':'No Late Informed'}}</td>
                                            <td>{{$inform->reason}}</td>
                                            <td class="text-primary lead">
                                                <a href="{{route('inform.edit',$inform->id)}}"><span class="fa fa-edit"></span></a>
                                                <a  data-value="{{$inform->id}}"  class="delete_link" href="#" >
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
                    <div class="bootstrap-iso">
                        {{$informs->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="deleteMyModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DELETE INFORM</h5>
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
    <script type="text/javascript">
        $(function () {
            // var FromEndDate = new Date();
            $('#start_date').datetimepicker({format:'L'
            });
            $('#end_date').datetimepicker({
                format:'L',
            });
        });
        $("#start_date").on("dp.change", function (e) {

            $('#end_date').data("DateTimePicker").minDate(e.date);
        });
        $("#end_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
        $("#filter").change(function() {

            if($(this).val() =='custom'){
                $('.date_search').css('display','block');
            }if($(this).val() != 'custom'){
                $('.date_search').css('display','none');
            }

        });

    </script>
    <script>
        $(".delete_link").on('click',function() {
            var inform=$(this).data("value");
            $.get('/inform/'+ inform +'/',function (result) {
                $('#modal-body').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/inform/'+result.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                        '{{ csrf_field()}}'+
                ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModal').modal();
                console.log(result);

            })

        });
    </script>
@endsection