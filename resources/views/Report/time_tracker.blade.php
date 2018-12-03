@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Inaccuracy Report </title>
@endsection
@section('page_styles')

    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    {{--<link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">--}}
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Employee Time Tracker </h1>
        </header>
        @if (session('status'))
            <div class="alert alert-success hidden-print">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header slide">
                        <div class="row" style="margin-bottom: 20px">

                                <div class="col-md-4 " style="margin-top: 10px">


                                    <form class="previous" id ="previous" method="GET" action="{{route('time.tracking.search')}}" >
                                        <button class="paginate left" type="submit"><i></i><i></i></button>


                                    </form>
                                    <div class="date">
                                        <h4>
                                    {{\Carbon\Carbon::now()->toFormattedDateString()}}

                                        </h4></div>
                                    <form class="next" id ="next" method="GET" action="{{route('time.tracking.search')}}" >

                                        <button class="paginate right" type="submit"><i></i><i></i></button>

                                    </form>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>

                    </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Total Time Logged :</h4>
                                </div>
                                <div class="col-md-4">
                                    <h4>Total Time Spent :</h4>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                        <p class="tracker_time">{{\Carbon\Carbon::now()->toTimeString()}}</p><span class="bit_on"></span>
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div><div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>
                                <div class="tracker_image ">
                                    <a class="img-thumbnail" href="#">
                                        <img class="img-responsive" src="http://placehold.it/100x100" alt="">
                                    </a>
                                </div>


                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
<script>
    var pr = document.querySelector( '.paginate.left' );
    var pl = document.querySelector( '.paginate.right' );
    pr.onclick = slide.bind( this, -1 );
    pl.onclick = slide.bind( this, 1 );
    var index = 0, total = 5;
    function slide(offset) {
        index = Math.min( Math.max( index + offset, 0 ), total - 1 );
        document.querySelector( '.counter' ).innerHTML = ( index + 1 ) + ' / ' + total;
        pr.setAttribute( 'data-state', index === 0 ? 'disabled' : '' );
        pl.setAttribute( 'data-state', index === total - 1 ? 'disabled' : '' );
    }
    slide(0);
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