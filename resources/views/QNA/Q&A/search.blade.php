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
                                <div class="col-lg-4">
                                    <div class="input-group input-group-md">
                                        <input class="form-control" placeholder="Search by Question No" id="name" type="number" name="name">
                                        <div class="input-group-append">
                                            <button type="button" class="print btn btn-outline-success ">Search</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-3 ">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">

                                </div>

                            </div>
                        </div>

                        <div class="card-body unique">



                        </div>

                </div>

            </div>
        </div>
    </div>

@endsection
@section('page_scripts')
    <script>

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
;                $('.unique').html('<div class=""> <div class=""> <div class="card custom-print"> <div class="card-body">'+
                    '<p class="card-text"><b>Question # '+data.id+' :</b>'+ data_question.replace(new RegExp('\r?\n','g'), '<br />') +'</p>'+
                    '<img style="width: 100px;height: 100px" class="img-responsive " src='+data_image+' alt='+data_question+'>'+

                    '<p class="card-text"><b>Answer :</b>'+data_answer.replace(new RegExp('\r?\n','g'), '<br />')+'</p>'+
                    '</div>'+
                    '</div>'+
                    '</div>');
                console.log(data);

            })

        });
        $('#button_clear').click(function(){
            $('#category input[type="text"]').val('');

        });
    </script>
@endsection