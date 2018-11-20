@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Inaccuracy Report </title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Employee Inaccuracy Report </h1>
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
                                <form class="filter_form" id ="filter_form" method="GET" action="{{route('admin.monthly.inaccuracy.search')}}" >
                                    {{--{{ csrf_field() }}--}}
                                <div class="row">
                                      <div class="form-group-material  col-sm-2 left-print" style="margin-top: 23px;">
                                          <div class='bootstrap-iso input-group-material' >
                                                <input autocomplete="off" type='text' id='month' name="month" value="{{\Request::get('month')?\Request::get('month'):''}}" class="input-material" />

                                                <label for="month" style="left: 17px" class="label-material">Select Year/Month</label>
                                            </div>
                                            @if ($errors->has('month'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('month') }}</strong>
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
                                    <th>Attendance Date</th>
                                    <th>Hours Spent</th>
                                    <th>Hours Logged</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($employee_lists))
                                    @foreach($employee_lists as $item)
                                        @foreach($item as $user)

                                        <tr>
                                            <td>{{$user['name']}}</td>
                                            <td>{{$user['date']?$user['date']:''}}</td>
                                            <td>{{$user['spent_time']?$user['spent_time']:''}}</td>
                                            <td>{{$user['logged_time']?$user['logged_time']:''}}</td>
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
            $('#month').datetimepicker({format:'Y/M'
            });
        });

    </script>


@endsection