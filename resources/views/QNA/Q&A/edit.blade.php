@extends('layouts.app')
@section('title')
     <title> {{config('app.name')}} | Update QuestionAnswers </title>
@endsection
@section('body')
     
     <div class="container">
          <!-- Page Header-->
          <header class="page-header">
               <h1 class="h3 display">Update Questions and their Answers</h1>
          </header>
          @if (session('status'))
               <div class="alert alert-success">
                    {{ session('status') }}
               </div>
          @endif
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         
                         <div class="card-body table_show">
                              @if(isset($question))
                                   <form class="form-horizontal" id ="question-answer form-sdf " method="POST" action="{{route('question-answers.update',$question->id)}}" enctype="multipart/form-data" >
                                   {{csrf_field()}}
                                        {{method_field('PATCH')}}
                                        <div class="row">
                                             <input type="hidden" id="url" value="{{route('question-answers.update',$question->id)}}">
                                             <div class="form-group-material col-md-4">
                                                  <label for="category" class="select-label form-control-label ">Category Type</label>
                                                  <select name="category" id="category" class="form-control filters ">
                                                       <option>Please Choose</option>
                                                       @php
                                                            $categories=\App\QNACategory::all()->sortByDesc('id');
                                                       @endphp
                                                       @if(isset($categories))
                                                            @foreach($categories as $category)
                                                                 <option {{$category->id == $question->category->id?"selected":''}} value="{{$category->id}}">{{$category->name}}</option>
                                                            @endforeach
                                                       @endif
                                                  </select>
                         
                                                  @if ($errors->has('category'))
                                                       <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                                  @endif
                                             </div>
                                             <div class="form-group-material col-md-3 align_center_question_input">
                         
                                                  <input type='number' id="marks" name="marks"   value="{{$question->marks}}" class="input-material" />
                                                  @if ($errors->has('marks'))
                                                       <span class="help-block">
                                        <strong>{{ $errors->first('marks') }}</strong>
                                    </span>
                                                  @endif
                                                  <label for="name" class="label-material active">Marks of Question</label>
                                             </div>
                                             <div class="form-group-material col-md-3 align_center_question_input">
                         
                                                  <input type='number' id="variation" name="variation"   value="{{$question->variation}}" class="input-material" />
                                                  @if ($errors->has('variation'))
                                                       <span class="help-block">
                                        <strong>{{ $errors->first('variation') }}</strong>
                                    </span>
                                                  @endif
                                                  <label for="name" class="label-material active">Variation of Question</label>
                                             </div>
                                             <div class="form-group row of-button col-md-2 " >
                                                  <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Approved</label>
                         
                                                  <label class="switch" class="col-sm-offset-3 ">
                                                       <input type="checkbox" name="proved" {{$question->proved?'checked':''}}>
                                                       <span class="slider round"></span>
                                                  </label>
                                             </div>
                                        </div>
                                        <div class="form-group-material">
                                             <label for="question" class="select-label form-control-label ">Brief Question</label>
                                             <div class="col-sm-12  mb-12 ">
                                                  <textarea  rows="5" name="question" class="form-control">{{$question->question}}</textarea>
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
                                                  <textarea rows="5" name="answer" class="form-control">{{$question->answer}}</textarea>
                                             </div>
                                             @if ($errors->has('answer'))
                                                  <span class="help-block">
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
                                             @endif
                                        </div>
               
               
                                        <div class="form-group-material">
                                             <input id="image" type="file" class="input-material" name="image" accept="image/x-png,image/gif,image/jpeg">
                                             <label for="image" class="label-material active">Image Of Question</label>
                                             @if ($errors->has('image'))
                                                  <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                             @endif
                                        </div>
                                        <button type="submit" class="btn btn-outline-success" href="javascript:;" id="comment">Update Question</button>
                                        <button type="button" id="button_clear" class="btn btn-outline-danger">
                                             Reset
                                        </button>
                                   </form>
     
                              @endif

                         </div>
                    
                    </div>
               </div>
          </div>
     </div>
@endsection
@section('page_scripts')

@endsection