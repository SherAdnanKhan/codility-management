@if(isset($leave))
<form class="form-horizontal" method="POST" action= "{{ route('leave.update', $leave->id) }}">
{{ csrf_field() }}
{{ method_field('PATCH') }}

    <div class="form-group-material">
        <label for="name" class="label-material">Leave Type</label>
        <div class='input-group-material ' >
            <input autocomplete="off" type='text' id="name" name="name"   value="{{$leave->name}}" class="input-material" />
            @if ($errors->has('name'))
                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
            @endif

        </div>
    </div>
    <div class="form-group-material">
        <label for="name" class="label-material">Leave Allowed</label>
        <input type='number' id="allowed" name="allowed"   value="{{$leave->allowed}}" class="input-material" />
        @if ($errors->has('allowed'))
            <span class="help-block">
                                        <strong>{{ $errors->first('allowed') }}</strong>
                                    </span>
        @endif

    </div>
    <div class="form-group row of-button" >
        <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label " >Is Public Holiday</label>
        <label class="switch col-sm-offset-3 " style="position: absolute ;margin-left: 79%;margin-top: -2%;"  >
            <input type="checkbox" name="public_holiday" {{$leave->public_holiday == true ?'checked':''}}>
            <span class="slider round"></span>
        </label>
    </div>
    <div class="form-group-material">
        <label for="color" class="label-material">Color</label>
        <input type="color" name="color_code" class=" col-2" value="{{$leave->color_code}}"/>
        @if ($errors->has('color_code'))
            <span class="help-block">
                                        <strong>{{ $errors->first('color_code') }}</strong>
                                    </span>
        @endif
    </div>
    <button type="submit" class="btn btn-outline-success">Update Leaves</button>
</form>
@endif
@if(isset($category))
    <form class="form-horizontal" method="POST" action= "{{ route('category.update', $category->id) }}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="form-group-material">
            <label for="name" class="label-material">Category Name</label>
            <div class='input-group-material ' >
                <input autocomplete="off" type='text' id="name" name="name"   value="{{$category->name}}" class="input-material" />
                @if ($errors->has('name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                @endif

            </div>
        </div>
        <button type="submit" class="btn btn-outline-success">Update Category</button>
    </form>

@endif
@if(isset($question))
    <form class="form-horizontal" id ="question-answer" method="POST" action="{{route('question-answers.update',$question->id)}}" enctype="multipart/form-data" >
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="row">
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
        <button type="submit" class="btn btn-outline-success">Update Question</button>
        <button type="button" id="button_clear" class="btn btn-outline-danger">
            Reset
        </button>
    </form>

    @endif


@if(isset($request_leave))
    
    <form class="form-horizontal" id ="request_leave" method="POST" action="{{route('request.update',$request_leave->id)}}" enctype="multipart/form-data" >
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="row">
            <div class="form-group-material col-md-5 col-md-offset-2">
                <label for="leave" class="select-label form-control-label ">Leave Approved As</label>
                <select name="leave" id="leave" class="form-control filters ">
                    <option value="">Please Choose</option>
                    @php
                        $leaves=\App\Leave::all()->sortByDesc('id');
                    @endphp
                    @if(isset($leaves))
                        @foreach($leaves as $leave)
                            <option {{$leave->id == ($request_leave->inform_id != null ?$request_leave->inform->id:'')?"selected":''}} value="{{$leave->id}}">{{$leave->name}}</option>
                        @endforeach
                    @endif
                </select>
                
                @if ($errors->has('leave'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('leave') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="form-group row of-button col-md-2 " >
                <label for="comment" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Approved</label>
                
                <label class="switch" class="col-sm-offset-3 ">
                    <input type="checkbox" name="approved" {{$request_leave->approved?'checked':''}}>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-success">Request Approved</button>
        <button type="button" id="button_clear" class="btn btn-outline-danger">
            Reset
        </button>
    </form>

@endif