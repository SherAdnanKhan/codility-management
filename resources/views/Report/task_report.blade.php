@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Task </title>
@endsection
@section('page_styles')

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Hours logged on Porject</h1>
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
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>The Total Logged Time on this project :</b> {{sprintf("%02d:%02d", floor($total_minutes / 60), $total_minutes % 60)}}
        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 hidden-print">
                                    <div class="" style=" float: right;margin-bottom: -30px">
                                        <button type="button" class="btn btn-outline-dark printReport" onclick="myFunction()"><span class="fa fa-print"></span> Print Report</button>

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
                                    <th>Task Date</th>
                                    <th>Task Timing</th>
                                    <th>Project</th>
                                    <th>Task Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($get_task)
                                    @foreach($get_task as $task)
                                        <tr>
                                            <td>{{$task->user->name}}</td>
                                            <td>{{$task->date}}</td>
                                            <td>{{$task->time_take}}</td>
                                            <td>{{$task->project_id != null ?$task->projects->project_name:''}}</td>
                                            <td>{{$task->description}}</td>
                                            
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


@endsection