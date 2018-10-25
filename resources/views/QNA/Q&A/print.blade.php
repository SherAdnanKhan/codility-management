@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | QuestionAnswers </title>
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Print Test</h1>
        </header>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form class="form-horizontal" id="print" method="POST" action="{{route('print.create')}}">
                    <div class="card-header">

                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-lg-3">
                                <button type="submit" class="btn btn-outline-success">Print Test Paper</button>
                            </div>
                            <div class="col-lg-9 ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                
                            </div>

                        </div>
                    </div>
                    <div class="card-body">

                            {{ csrf_field() }}
                            <div class="form-group-material">
                                <label class=" label-material" style="font-weight: lighter">Select Categories</label>
                            </br>

                                    @if($category)

                                        @foreach($category as $get_category)
                                    <label class="checkbox-inline">
                                        <input id="{{$get_category->name}}" name="print[]"  type="checkbox" value="{{$get_category->id}}"> {{$get_category->name}}
                                    </label>
                                        @endforeach
                                    @endif

                                @if ($errors->has('print'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('print') }}</strong>
                                    </span>
                                @endif
                            </div>



                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
