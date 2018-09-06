@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} |Employee Dashboard </title>
@endsection
@section('body')
    <div class="container" >
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    {{--<div class="panel-heading">Dashboard</div>--}}
                    <div class="panel-body text-capitalize text-center" >
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h1 style="text-align: center">Welcome</h1>
                        <h2 style="text-align: center">{{\Auth::user()->name}}</h2>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}
                {{--<img src="{{asset('images/banneremploye.jpg')}}">--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
@endsection
