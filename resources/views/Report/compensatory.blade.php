@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Report </title>
@endsection
@section('page_styles')

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Yearly Leave Report</h1>
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
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id='printMe'>
                                <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Allotted Leaves</th>
                                    <th>Compensatory Leaves</th>
                                    <th>Use Leaves</th>
                                    <th>Remaining Leaves</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($users))
                                    @foreach($users as $user)
                                        
                                        <tr>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->allotted_leaves}}</td>
                                            <td>{{$user->compensatory_leaves}}</td>
                                            <td>{{$user->count_use_leaves}}</td>
                                            <td>{{($user->compensatory_leaves + $user->allotted_leaves ) - $user->count_use_leaves}}</td>
                                            
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

