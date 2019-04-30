@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | QuestionAnswers </title>
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Search Test Question</h1>
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
                                    <div class="input-group input-group-md">
                                        <input class="form-control" placeholder="Search by Question No" id="name" type="number" name="name">
                                        <div class="input-group-append">
                                            <button type="button" class="print btn btn-outline-success ">Search</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-8 ">
                                    <form class="form-horizontal form-inline" id ="searchByCategory" method="POST" action="{{route('searchQuestionByCategory')}}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <div class="form-group-material">
                                            
                                            <select name="category" id="category" class="form-control filters ">
                                                <option value="">Search By Category Type</option>
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
                                        <div class="form-group-material input-group input-group-md searchByText">
                                            <input class="form-control" placeholder="Search by Text" id="text" type="text" name="text">
                                            <div class="input-group-append">
                                                <button type="submit" class="print btn btn-outline-success ">Search</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">

                                </div>

                            </div>
                        </div>

                        <div class="card-body unique">



                        </div>
                    @if(isset($question_answers))
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
                                
                                    @foreach($question_answers as $question_answer)
                                        <tr>
                                            <td>{{$question_answer->id}}</td>
                                            <td>{{$question_answer->category?$question_answer->category->name:'Without Category'}}</td>
                                            <td>{{$question_answer->proved?'Yes':'No'}}</td>
                                            <td>{{$question_answer->variation}}</td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$question_answer->question}}</textarea></td>
                                            <td><textarea class="form-control" cols="40" disabled="disabled">{{$question_answer->answer}}</textarea></td>
                        
                                        </tr>
                                    @endforeach
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bootstrap-iso">
                        {{$question_answers->links()}}
                    </div>
                    @endif
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

        });
    </script>
@endsection