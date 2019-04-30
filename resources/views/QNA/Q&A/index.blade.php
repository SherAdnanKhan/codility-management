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
                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-lg-3">
                                <div class="input-group input-group-md">
                                    <input class="form-control" placeholder="Search by Question No" id="name" type="number" name="name">
                                    <div class="input-group-append">
                                        <button type="button" class="print btn btn-outline-success ">Search</button>
                                    </div>
                                </div>
        
                            </div>
                            <div class="col-lg-8 ">
                                <form class="form-horizontal form-inline" id ="searchByCategory" method="GET" action="{{route('searchQuestionByCategoryAdmin')}}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group-material">
                    
                                        <select name="category" id="category" class="form-control filters ">
                                            <option value="">Search By Category Type</option>
                                            @php
                                                $categories=\App\QNACategory::all()->sortByDesc('id');
                                            @endphp
                                            @if(isset($categories))
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" {{\Request::get('category')==$category->id?'selected ':''}}>{{$category->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                    
                                        @if ($errors->has('category'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="form-group-material input-group input-group-md searchByText">
                                        <input class="form-control" placeholder="Search by Text" id="text" type="text" name="text" value="{{\Request::get('text')?\Request::get('text'):''}}">
                                        <div class="input-group-append">
                                            <button type="submit" class="print btn btn-outline-success ">Search</button>
                                        </div>
                                    </div>
                                </form>
        
                            </div>
                        </div>
                    </div>
                    <div class="card-body unique">
    
    
    
                    </div>
                    
    
                    <div class="card-body table_show">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Question No</th>
                                    <th>Category Name</th>
                                    <th>Approved</th>
                                    <th>Variation type </th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (session('search_question_answers'))
                                    @foreach(session('search_question_answers') as $question_answer)
                                        <tr>
                                            <td>{{$question_answer->id}}</td>
                                            <td>{{$question_answer->category?$question_answer->category->name:'Without Category'}}</td>
                                            <td>{{$question_answer->proved?'Yes':'No'}}</td>
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
                                @if($question_answers && session('search_question_answers') == null)
                                    @foreach($question_answers as $question_answer)
                                        <tr>
                                            <td>{{$question_answer->id}}</td>
                                            <td>{{$question_answer->category?$question_answer->category->name:'Without Category'}}</td>
                                            <td>{{$question_answer->proved?'Yes':'No'}}</td>
                                            <td>{{$question_answer->variation}}</td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$question_answer->question}}</textarea></td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$question_answer->answer}}</textarea></td>

                                            <td><a  style="color:green "data-value="{{$question_answer->id}}"  class="edit_link" href="{{route('question-answers.edit',$question_answer->id)}}" >
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
                    @if($question_answers && session('search_question_answers') == null)
                    <div class="bootstrap-iso table_show">
                        {{$question_answers->links()}}
                    </div>
                    @endif
                    
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
                    <form class="form-horizontal" id ="question-answer" method="POST" action="{{route('question-answers.store')}}" enctype="multipart/form-data">
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
                                <textarea  name="question" class="form-control ">{{old('question')}}</textarea>
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
                                <textarea  name="answer" class="form-control">{{old('answer')}}</textarea>
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
                        <div class="form-group-material">
                            <input id="image" type="file" class="input-material" name="image" accept="image/x-png,image/gif,image/jpeg">
                            @if ($errors->has('image'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                            @endif
                        </div>
                        <div class="form-group row of-button  " >
                            <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Approved</label>
        
                            <label class="switch" class="col-sm-offset-3 ">
                                <input type="checkbox" name="proved" >
                                <span class="slider round"></span>
                            </label>
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
        // $(".edit_link").on('click',function() {
        //     var question_answer=$(this).data("value");
        //
        //     $.get('/question-answers/'+question_answer+'/edit',function (data) {
        //         // $.each(data,function (index,state) {
        //         $('#update-modal-body').html(data);
        //         $('#updateModal').modal();
        //         console.log(data);
        //
        //     })
        //
        // });
        $("#category").on('change',function() {
            $('#searchByCategory').submit();
            $('.unique').hide();
    
        });
        $(".print").on('click',function() {
            var question_answer= $('#name').val();
            $.get('/search/question/'+question_answer+'/',function (data) {
                var data_question=data.question;
                var data_answer=data.answer;
                
                if (data.image != null){
                    var data_image= "{!! asset('images/question') !!}"+'/'+data.image;
                }else {
                    var data_image='';
                }
                if (data.image != null) {
                    $('.unique').show();
                    $('.table_show').hide();
                    $('.unique').html('<div class=""> <div class=""> <div class="card custom-print"> <div class="card-body">' +
                            '<p class="card-text"><b>Question # ' + data.id + ' :</b>' + data_question.replace(new RegExp('\r?\n', 'g'), '<br />') + '</p>' +
                            '<img style="width: 100px;height: 100px" class="img-responsive " src=' + data_image + ' alt=' + data_question + '>' +
            
                            '<p class="card-text"><b>Answer :</b>' + data_answer.replace(new RegExp('\r?\n', 'g'), '<br />') + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                }else {
                    $('.unique').show();
                    $('.table_show').hide();
                    $('.unique').html('<div class=""> <div class=""> <div class="card custom-print"> <div class="card-body">' +
                            '<p class="card-text"><b>Question # ' + data.id + ' :</b>' + data_question.replace(new RegExp('\r?\n', 'g'), '<br />') + '</p>' +
                            '<p class="card-text"><b>Answer :</b>' + data_answer.replace(new RegExp('\r?\n', 'g'), '<br />') + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                }
                
            })
    
        });
        $('#button_clear').click(function(){
            $('#category input[type="text"]').val('');
    
        })
        $( "body" ).on( "click", "#comment", function() {
            alert($("#category").val());
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                    type: "PATCH",
                    url: $("#url").val(),
                    data: { category:$("#category").val()},
                    success: function(msg) {
                        alert(msg);
                    }
                });
        });
        // $('#comment').on('submit', function(e) {
        //    alert('s');
        //     // e.preventDefault();
        //     // $.ajax({
        //     //     type: "POST",
        //     //     url: host+'/comment/add',
        //     //     data: $(this).serialize(),
        //     //     success: function(msg) {
        //     //         alert(msg);
        //     //     }
        //     // });
        // });
    </script>
@endsection