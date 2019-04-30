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
            <input type="checkbox" name="public_holiday" id="public_holidays" {{$leave->public_holiday == true ?'checked':''}}>
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
    <div class="date_of_holidays">
        @if($leave->public_holiday == true)
            <div class='form-group-material'>
                <div class=' bootstrap-iso input-group-material date' >
                    <input  type='text' id='date' name='date' class='input-material' value="{{\Carbon\Carbon::createFromTimestamp($leave->date)->format('m/d/Y')}}"/>
                    <label for='date' class='label-material active '>Date Of Public Holiday</label>
                </div>
                @if($errors->has('date'))
                    <span class='help-block'>
                        <strong>{{$errors->first('date') }}</strong>
                    </span>
                @endif
            </div>
            @endif
    </div>
    <button type="submit" class="btn btn-outline-success">Update Leaves</button>
</form>

@endif
@if(isset($projects))
    <form class="form-horizontal" method="POST" action= "{{ route('project.update', $projects->id) }}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="form-group-material">
            <div class='  input-group-material ' >
                <input autocomplete="off" type='text' id='project_title' name="project_title"   value="{{$projects->project_name}}" class="input-material" />
            
                <label for="attendance" class=" active label-material">Project Title</label>
            </div>
            @if ($errors->has('project_title'))
                <span class="help-block">
                                            <strong>{{ $errors->first('project_title') }}</strong>
                                        </span>
            @endif
        </div>
  
        <button type="submit" class="btn btn-outline-success">Update</button>
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
    <form class="form-horizontal" id ="question-answer form-sdf " method="POST" action="{{route('question-answers.update',$question->id)}}" enctype="multipart/form-data" >
        
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
        <a class="btn btn-outline-success" href="javascript:;" id="comment">Update Question</a>
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
                            <option {{$leave->id == $selected_leave?"selected":''}} value="{{$leave->id}}">{{$leave->name}}</option>
                        @endforeach
                    @endif
                </select>
                
                @if ($errors->has('leave'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('leave') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="form-group of-button decline_group" >
                <div class="switch-field">
                    <div class="switch-title">Marked Status </div>
                    <input type="radio" id="switch_3_left" name="status" value="approved" {{$request_leave->approved == 'Approved '?'checked':''}}/>
                    <label for="switch_3_left">Approved</label>
                    <input type="radio" id="switch_3_center" name="status" value="not_approved" {{$request_leave->approved == 'Not Approved '?'checked':''}}/>
                    <label for="switch_3_center">Not Approved</label>
                    <input type="radio" id="switch_3_right" name="status" value="declined" {{$request_leave->approved == 'Declined'?'checked':''}} />
                    <label for="switch_3_right">Declined</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-success">Update Request</button>
        <button type="button" id="button_clear" class="btn btn-outline-danger">
            Reset
        </button>
    </form>
    
@endif

@if(isset($interview))
    <form class="form-horizontal" id="timetable" method="POST" action="{{route('interview.update',$interview->id)}}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="row">
            
            @php
                $selected_status=\App\Status::whereId($interview->status_id)->first();
            @endphp
            @if(isset($selected_status))
                
                @if($selected_status->sub_status)
            <div class="col-md-3">
                <div  class="form-group-material  view">
                    
                    <div class='col-sm-12  mb-12 '>
                        <select name='sub_status'  class='form-control  ajax'>
                            @foreach($selected_status->sub_status as $sub_status)
                                <option {{$interview->sub_status != null ? ($interview->sub_status == $sub_status->id ?'selected':''):''}} value="{{$sub_status->id}}">{{$sub_status->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('sub_status'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('sub_status') }}</strong>
                                        </span>
                        @endif
                    </div>
                </div>
            </div>
                @endif
                @endif
        </div>
        
        <div class="form-group row">
            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Brief Note</label>
            <div class="col-sm-12  mb-12 ">
                <textarea  name="description" class="form-control">{{$sub_status->description?$sub_status->description:''}}</textarea>
            </div>
            @if ($errors->has('description'))
                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="applicant_core_id">
        
        </div>
        
        <button type="submit" class="btn btn-outline-success">Submit</button>
        <button type="button" id="button_clear" class="btn btn-outline-danger">
            Reset
        </button>
    </form>
@endif