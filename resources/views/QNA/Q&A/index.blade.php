@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | QuestionAnswers </title>
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Questions and their Answers</h1>
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
                                <a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"
                                   href="#createMyModal">Add Question-Answer</a>
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
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Question No</th>
                                    <th>Category Name</th>
                                    <th>Variation type </th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($question_answers)
                                    @foreach($question_answers as $question_answer)
                                        <tr>
                                            <td>{{$question_answer->id}}</td>
                                            <td>{{$question_answer->category?$question_answer->category->name:'Without Category'}}</td>
                                            <td>{{$question_answer->variation}}</td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$question_answer->question}}</textarea></td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$question_answer->answer}}</textarea></td>

                                            <td><a  style="color:green "data-value="{{$question_answer->id}}"  class="edit_link" href="#" >
                                                    <span class="fa fa-edit"></span>
                                                </a>
                                                <a   style="color:red;" data-value="{{$question_answer->id}}"  class="delete_link" href="#" >
                                                    <span class="fa fa-times"></span></a></td>

                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bootstrap-iso">
                        {{$question_answers->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="createModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Please Add Question with their Answer</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id ="question-answer" method="POST" action="{{route('question-answers.store')}}" >
                        {{ csrf_field() }}
                        <div class="form-group-material  ">
                            <label for="category" class="select-label form-control-label ">Category Type</label>
                            <select name="category" id="category" class="form-control filters ">
                                <option>Please Choose</option>
                                @php
                                $categories=\App\QNACategory::all()->sortByDesc('id');
                                        @endphp
                                @if(isset($categories))
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                    @endif
                            </select>

                            @if ($errors->has('category'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group-material">
                            <label for="question" class="select-label form-control-label ">Brief Question</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="question" class="form-control ">
                                    {{old('question')}}
                                </textarea>
                            </div>
                            @if ($errors->has('question'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('question') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group-material">
                            <label for="answer" class="select-label form-control-label ">Brief Answer of Question</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="answer" class="form-control">
                                    {{old('answer')}}
                                </textarea>
                            </div>
                            @if ($errors->has('answer'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group-material">

                            <input type='number' id="marks" name="marks"   value="" class="input-material" />
                            @if ($errors->has('marks'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('marks') }}</strong>
                                    </span>
                            @endif
                            <label for="name" class="label-material">Marks of Question</label>
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit Question</button>
                        <button type="button" id="button_clear" class="btn btn-outline-danger">
                            Reset
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <div id="updateModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Update Question with their Answer</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="update-modal-body">


                </div>

            </div>
        </div>
    </div>

    <div id="deleteMyModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">DELETE QUESTION</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="delete-modal-body">

                </div>

            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script>
        $(".delete_link").on('click',function() {
            var question_answer=$(this).data("value");
            $.get('/question-answers/'+ question_answer +'/',function (result) {
                $('#delete-modal-body').html("Are You Sure Delete ("  +result.question+ ") QUESTION."+
                    '<form class=form-inline" method="POST"  action ="/question-answers/'+result.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModal').modal();
                console.log(result);

            })
        });
        $(".edit_link").on('click',function() {
            var question_answer=$(this).data("value");

            $.get('/question-answers/'+question_answer+'/edit',function (data) {
                // $.each(data,function (index,state) {
                $('#update-modal-body').html(data);
                $('#updateModal').modal();
                console.log(data);

            })

        });
        $('#button_clear').click(function(){
            $('#category input[type="text"]').val('');

        });
    </script>
@endsection