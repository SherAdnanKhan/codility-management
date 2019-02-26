@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Report </title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Employee Leaves Report</h1>
        </header>
        @if (session('status'))
            <div class="alert alert-success hidden-print">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-lg-3">

                            </div>
                            <div class="col-lg-9 hidden-print">
                                <div class="col-lg-offset-6 " style=" float: right;margin-bottom: -30px">
                                    <button type="button" class="btn btn-outline-dark printReport" onclick="myFunction()"><span class="fa fa-print"></span> Print Report</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('admin.report.search')}}" >
                                    {{--{{ csrf_field() }}--}}
                                <div class="row">
                                      <div class="form-group-material  col-sm-2 left-print" style="margin-top: 23px;">
                                          <div class='bootstrap-iso input-group-material' >
                                                <input autocomplete="off" type='text' id='start_date' name="start_date" value="{{\Request::get('start_date')?\Request::get('start_date'):''}}" class="input-material" />

                                                <label for="start_date" style="left: 17px" class="label-material">Start Date Form</label>
                                            </div>
                                            @if ($errors->has('start_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                            @endif

                                    </div>
                                    <div class="form-group-material  col-sm-2 right-print" style="margin-top: 23px;">

                                        <div class=' bootstrap-iso input-group-material date' >
                                            <input autocomplete="off" type='text' id='end_date' name="end_date" value="{{\Request::get('end_date')?\Request::get('end_date'):''}}" class="input-material" />

                                            <label for="end_date" style="left: 17px" class="label-material">End Date Form</label>
                                        </div>
                                        @if ($errors->has('end_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                        @endif

                                    </div>
                                    <div class="col-sm-1 hidden-print" style="margin-top: 27px;">
                                    <button type="submit" class="btn btn-outline-success">Generate Report</button>
                                    </div>

                                </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id='printMe'>
                                <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Total Leaves</th>
                                    <th>Total allowed Leaves</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($user_details))

                                    @foreach($user_details as $item)
                                        @foreach($item as $user)
                                        <tr>
                                            <td>{{$user['name']}}</td>
                                            <td>{{$user['total_absent']}}</td>
                                            <td>{{$user['allowed_absent']}}</td>
                                        </tr>
                                            @endforeach
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
@endsection
@section('page_scripts')
        <script>
            function myFunction() {
                window.print();
            }
    </script>
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

    </script>


@endsection