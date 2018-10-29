@if(isset($leave))
<form class="form-horizontal" method="POST" action= "{{ route('leave.update', $leave->id) }}">
{{ csrf_field() }}
{{ method_field('PATCH') }}

    <div class="form-group-material">
        <label for="name" class="label-material">Leave Type</label>
        <div class='input-group-material ' >
            <input type='text' id="name" name="name"   value="{{$leave->name}}" class="input-material" />
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
                <input type='text' id="name" name="name"   value="{{$category->name}}" class="input-material" />
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
    <form class="form-horizontal" id ="question-answer" method="POST" action="{{route('question-answers.update',$question->id)}}" >
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="form-group-material">
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
        <div class="form-group-material">
            <label for="question" class="select-label form-control-label ">Brief Question</label>
            <div class="col-sm-12  mb-12 ">
                                <textarea  name="question" class="form-control">{{$question->question}}</textarea>
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
                                <textarea  name="answer" class="form-control">{{$question->answer}}</textarea>
            </div>
            @if ($errors->has('answer'))
                <span class="help-block">
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="form-group-material">

            <input type='number' id="marks" name="marks"   value="{{$question->marks}}" class="input-material" />
            @if ($errors->has('marks'))
                <span class="help-block">
                                        <strong>{{ $errors->first('marks') }}</strong>
                                    </span>
            @endif
            <label for="name" class="label-material active">Marks of Question</label>
        </div>
        <div class="form-group-material">

            <input type='number' id="variation" name="variation"   value="{{$question->variation}}" class="input-material" />
            @if ($errors->has('variation'))
                <span class="help-block">
                                        <strong>{{ $errors->first('variation') }}</strong>
                                    </span>
            @endif
            <label for="name" class="label-material active">Variation of Question</label>
        </div>

        <button type="submit" class="btn btn-outline-success">Update Question</button>
        <button type="button" id="button_clear" class="btn btn-outline-danger">
            Reset
        </button>
    </form>

    @endif