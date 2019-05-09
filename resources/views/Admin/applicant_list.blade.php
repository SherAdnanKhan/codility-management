@extends('layouts.app')
@section('title')
    <title xmlns="http://www.w3.org/1999/html"> {{config('app.name')}} | Applicants</title>
@endsection
@section('page_styles')
    
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-tagsinput.css')}}">
@endsection
@section('body')
    <div class="container">
        <header class="page-header">
            <h1 class="h3 display">Applicant List</h1>
        </header>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-9">
                        <a data-target="#createModal" data-toggle="modal" class="btn btn-outline-success"
                           id="MainNavHelp"
                           href="#createModal">Add Applicant </a>
                        <a  class="btn btn-outline-success"
                           id="MainNavHelp"
                           href="{{route('upload.cvs')}}"><i class="fa fa-upload"></i> Add Bulk Applicants </a>
                        <a data-target="#createMyModal" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"
                           href="#createMyModal"> <i class="fa fa-cog"></i> Manage Status</a>
                        <a data-target="#addDropDownInterviewFor" data-toggle="modal" class="btn btn-outline-success" id="MainNavHelp"
                           href="#addDropDownInterviewFor"> <i class="fa fa-cog"></i> Manage Interview for(Dropdown)</a>
                    </div>
                    
                </div>
                        <div class="row">
                        {{--<form method="GET" action="{{route('applicant_list')}}">--}}
                            {{--<div class="col-md-3">--}}
                                {{--<div class="form-group-material " >--}}
                                    {{--<div class="">--}}
                                    
                                    {{--</div>--}}
                                    {{--@if ($errors->has('source'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('source') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-lg-3">--}}
                                {{--<div class="form-group-material ">--}}
                                    {{--<div class='input-group-material'>--}}
                                        {{--<input autocomplete="off" type='text' id='applicantId' name="applicantId"--}}
                                               {{--value="" class="input-material"/>--}}
                {{----}}
                                        {{--<label for="applicantId" class="label-material">Applicant ID</label>--}}
                                    {{--</div>--}}
                                    {{--@if ($errors->has('applicantId'))--}}
                                        {{--<span class="help-block">--}}
                                            {{--<strong>{{ $errors->first('applicantId') }}</strong>--}}
                                        {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{----}}
   {{----}}
                                    {{--<button type="submit" style="position: relative; float: right;" class="btn btn-outline-success clear">Search</button>--}}


                                {{----}}
                        {{--</form>--}}
                            <div class="col-lg-12">
                                <form class="filter_form" id ="filter_form"  method="GET" action="{{route('applicant_list')}}" >
                                    {{--{{ csrf_field() }}--}}
                                    <div class="row">
                                        <div class="form-group-material col-sm-3 " style="margin-top: 30px;">
                                            <select name="source" class="form-control ">
                                                <option value="">Search By Source</option>
                                                <option value="Rozee.pk">Rozee.pk</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Linkedin">Linkedin</option>
                                                <option value="Gmail">Gmail</option>
                                                <option value="Management">Management</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Codility">Codility</option>

                                            </select>
                    
                                            @if ($errors->has('source'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('source') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='query' name="query"
                                                value="{{app('request')->input('query')}}" class="input-material"/>
                                                <label for="name" class="label-material" style="left: 17px">Search by Keyword </label>
                                            </div>
                                            @if ($errors->has('query'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('query') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='email' name="email"
                                                       value="{{app('request')->input('email')}}" class="input-material"/>
                                                <label for="name" class="label-material" style="left: 17px">Search by Email </label>
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='applicant_id' name="applicant_id"
                                                       value="{{app('request')->input('applicant_id')}}" class="input-material"/>
                                                <label for="applicant_id" class="label-material" style="left: 17px">Search by Applicant ID</label>
                                            </div>
                                            @if ($errors->has('applicant_id'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('applicant_id') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='first_name' name="first_name"
                                                       value="{{app('request')->input('first_name')}}" class="input-material"/>
                                                <label for="first_name" class="label-material" style="left: 17px">Search by First Name</label>
                                            </div>
                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='last_name' name="last_name"
                                                       value="{{app('request')->input('last_name')}}" class="input-material"/>
                                                <label for="last_name" class="label-material" style="left: 17px">Search by Last Name</label>
                                            </div>
                                            @if ($errors->has('last_name'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group-material date" style="margin-top: 23px;">
                                                <div class=' bootstrap-iso input-group-material date_of_birth'>
                                                    <input autocomplete="off" type='text' id='date_of_birth' name="date_of_birth" value="{{app('request')->input('date_of_birth')}}"
                                                           class="input-material"/>
                
                                                    <label for="date_of_birth" class="label-material">Search By DOB</label>
                                                </div>
                                                @if ($errors->has('date_of_birth'))
                                                    <span class="help-block">
                                            <strong>{{ $errors->first('date_of_birth') }}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        @php
                                            $interview_for=\App\DropDown::all();
                                        @endphp
    
                                        <div class="col-sm-3" style="margin-top: 23px;">
                                            <div class="form-group-material">
                                                <div class='input-group-material'>
                                                    <select  name="search_for_interview" class="form-control " id='search_for_interview' >
                                                        <option value="">Search by Interview For</option>
                                                        @foreach($interview_for as $item)
                                                            <option value="{{$item->id}}">{{$item->interview_for}}</option>
                    
                                                        @endforeach
                
                                                    </select>
                                                </div>
                                                @if ($errors->has('search_for_interview'))
                                                    <span class="help-block">
                                            <strong>{{ $errors->first('search_for_interview') }}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group-material col-sm-3 "style="margin-top: 23px;">
                                            <div class='input-group-material'>
                                                <input autocomplete="off" type='text' id='serial_number' name="serial_number"
                                                       value="{{app('request')->input('serial_number')}}" class="input-material"/>
                                                <label for="serial_number" class="label-material" style="left: 17px">Search by Serial Number of Test</label>
                                            </div>
                                            @if ($errors->has('serial_number'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('serial_number') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-1 " style="margin-top: 27px;">
                                            <button type="submit" class="btn btn-outline-success">Search</button>
                                        </div>
            
                                    </div>
                                </form>
                            </div>
                        </div>
                {{--</div>--}}
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Source</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Area</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($applicants)>0)
                        
                        @foreach($applicants->all() as $applicant)
                            <tr>
                                <td>{{$applicant->applicantId}}</td>
                                <td>{{$applicant->date}}</td>
                                <td>@if($applicant->test()->first() ||  $applicant->interview()->first())
                                        <a href="{{route('get_single_applicant',$applicant->id)}}">{{$applicant->firstName}}{{$applicant->middleName?$applicant->middleName:''}}{{$applicant->LastName?$applicant->LastName:''}}
                                </a>
                                        @else
                                        {{$applicant->firstName}}{{$applicant->middleName?$applicant->middleName:''}}{{$applicant->LastName?$applicant->LastName:''}}
    
                                @endif
                                </td>
                                <td>{{$applicant->source?$applicant->source:'No Source Defined'}}</td>
                                <td>{{$applicant->phoneNumber}}</td>
                                <td>{{$applicant->email}}</td>
                                <td>{{$applicant->country?$applicant->country.'=>':''}}{{$applicant->city?$applicant->city:''}}</td>
                                <td>
                                        <a class="cvUploadLink" title="Upload CV" data-id='{{$applicant->id}}'
                                       href='{{url("/upload-cv/{$applicant->id}")}}'><i class="fa fa-upload"></i></a>
                                    &nbsp;
                                    @if($applicant->cvExt=="pdf")
                                        <?php $extClass = "far fa-file-pdf";
                                        $disabled = '';
                                        ?>
                                    @elseif($applicant->cvExt=="doc" || $applicant->cvExt=="docx")
                                        <?php $extClass = "far fa-file-word";
                                        $disabled = '';
                                        ?>
                                    @else
                                        <?php $extClass = "fas fa-eye-slash";
                                        $disabled = 'not-active';
                                        ?>
                                    @endif
                                    <a class="viewCvLink {{$disabled}}" title="View CV"
                                       href='{{url("/view-cv/{$applicant->id}")}}'><i class='{{$extClass}}'></i></a>
                                    @php
                                        $interview_check=$applicant->interview()->orderBy('id','desc')->first();
                                    
                                    @endphp
                                    &nbsp;
                                    
                                    @if(isset($interview_check))
                                        @if($interview_check->sub_status_id == null && $interview_check->status->sub_status->first() != null)
                                        {{--<a title="Edit Interview status" data-target="#editInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-edit"></i></a>--}}
                                        <a title="Edit Interview status" data-value='{{$applicant->id}}' class="edit_link" href="javascript://">
                                            <span class="fa fa-edit"></span>
                                        </a>
                                        @else
                                            <a title="Add Interview status" data-target="#addInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-plus"></i></a>
                                            &nbsp;
                                    @endif
                                    @else
                                        <a title="Add Interview status" data-target="#addInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-plus"></i></a>
                                        &nbsp;
                                    @endif
                                    <a class="deleteLink" title="Delete" href='{{url("/delete/{$applicant->id}")}}'><i
                                                class="fa fa-trash-alt"></i></a>
                                    
                                    <a title="Add Test Detail" data-target="#addTest" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-file"></i></a>
                                    <a title="Add Call Detail" data-target="#addCall" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id_call"><i class="fa fa-phone"></i></a>
    
                                    <a class="applicant_id" title="Send Email" href='{{url("/select/email/template/{$applicant->id}")}}'><i class='fa fa-paper-plane'></i></a></td>
                            </tr>
                           
                        @endforeach
                    @endif
                    </tbody>
                </table>
                @if(count($applicants)>0)
                        <div class="bootstrap-iso">
                        {{$applicants->links()}}
                        </div>
                @endif
            </div>
        </div>
    </div>
    <div id="createModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Applicant of Job</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    
                    <form class="form-horizontal" id="applicantadd" method="POST" action="{{route('applicant.manual')}}">
                        {{ csrf_field() }}
                        
                        <div class="row">
                            @if(\Session::get("already_exist") == true)
                                <div class="alert alert-success">
                                    <p>The following applicants are similar to new applicant, Please make sure its new applicant If its new then click as new applicant button</p>
                                </div>
                                <input autocomplete="off" type='hidden' value="force_submit" name="force_submit"/>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group-material ">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='applicantId' name="applicantId"
                                               value="{{old('applicantId')}}{{\Session::get("applicantId")?\Session::get("applicantId"):null}}" class="input-material"/>
                                        
                                        <label for="applicantId" class="label-material">Applicant ID (Optional)</label>
                                    </div>
                                    @if ($errors->has('applicantId'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('applicantId') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material ">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='firstName' name="firstName" value="{{old('firstName')}}{{\Session::get("firstName")?\Session::get("firstName"):null}}"
                                               class="input-material"/>
                                        
                                        <label for="firstName" class="label-material">First Name</label>
                                    </div>
                                    @if ($errors->has('firstName'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('firstName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='middleName' name="middleName" value="{{old('middleName')}}{{\Session::get("middleName")?\Session::get("middleName"):null}}"
                                               class="input-material"/>
                                        
                                        <label for="middleName" class="label-material">Middle Name
                                            (Optional)</label>
                                    </div>
                                    @if ($errors->has('middleName'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('middleName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='lastName' name="lastName" value="{{old('lastName')}}{{\Session::get("LastName")?\Session::get("LastName"):null}}"
                                               class="input-material"/>
                                        
                                        <label for="lastName" class="label-material">Last Name (Optional) </label>
                                    </div>
                                    @if ($errors->has('lastName'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lastName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material " style="margin: 7px 0 0 0px">
                                    <div class="">
                                        <select name="gender" class="form-control ">
                                            <option value="">Select Gender</option>
                                            
                                            <option value="male" {{\Session::get("gender") == 'male' ?'selected' :null}} {{old('gender') =='male'?'selected':null}}>Male</option>
                                            <option value="female" {{\Session::get("gender") =='female'?'selected':null}} {{old('gender') =='male'?'selected':null}}>Female</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material date">
                                    <div class=' bootstrap-iso input-group-material date'>
                                        <input autocomplete="off" type='text' id='date' name="date" value="{{old('date')}}{{\Session::get("date")?\Session::get("date"):null}}"
                                               class="input-material"/>
                                        
                                        <label for="date" class="label-material">Date Of Apply</label>
                                    </div>
                                    @if ($errors->has('date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material date">
                                    <div class=' bootstrap-iso input-group-material date'>
                                        <input autocomplete="off" type='text' id='dob' name="dob" value="{{old('dob')}}{{\Session::get("dob")?\Session::get("dob"):null}}"
                                               class="input-material"/>
                                        
                                        <label for="dob" class="label-material">Date of Birthday</label>
                                    </div>
                                    @if ($errors->has('dob'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='number' id='age' name="age" value="{{old('age')}}{{\Session::get("age")?\Session::get("age"):null}}"
                                               class="input-material"/>
                                        
                                        <label for="age" class="label-material">Applicant`s Age</label>
                                    </div>
                                    @if ($errors->has('age'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('age') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='nationality' name="nationality"
                                                class="input-material" value="{{\Session::get("nationality")?\Session::get("nationality"):'Pakistani'}}"/>
                                        
                                        <label for="nationality" class="label-material active">Applicant`s
                                            Nationality</label>
                                    </div>
                                    @if ($errors->has('nationality'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nationality') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='tel' id='phoneNumber' name="phoneNumber"
                                               value="{{old('phoneNumber')}}{{\Session::get("phoneNumber")?\Session::get("phoneNumber"):null}}" class="input-material" placeholder="9230000000000"/>
                                        
                                        <label for="phoneNumber" class="label-material active">Applicant`s Contact
                                            Number</label>
                                    </div>
                                    @if ($errors->has('phoneNumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phoneNumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='currentSalary' name="currentSalary"
                                               value="{{old('currentSalary')}} {{\Session::get("currentSalary")?\Session::get("currentSalary"):null}}" class="input-material"/>
                                        
                                        <label for="currentSalary" class="label-material">Current Salary</label>
                                    </div>
                                    @if ($errors->has('currentSalary'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('currentSalary') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='expectedSalary' name="expectedSalary"
                                               value="{{old('expectedSalary')}}{{\Session::get("expectedSalary")?\Session::get("expectedSalary"):null}}" class="input-material"/>
                                        
                                        <label for="expectedSalary" class="label-material">Expected Salary</label>
                                    </div>
                                    @if ($errors->has('expectedSalary'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('expectedSalary') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='email' name="email"
                                               value="{{old('email')}}{{\Session::get("email")?\Session::get("email"):null}}" class="input-material"/>
                
                                        <label for="email" class="label-material">Email</label>
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @php
                                $interview_for=\App\DropDown::all();
                            @endphp
    
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        
                                        <select  name="applicant_interview_for" class="form-control " id='applicant_interview_for' >
                                            <option value="">Interview For</option>
                                            @foreach($interview_for as $item)
                                                <option value="{{$item->id}}" {{\Session::get("interview_for") == $item->interview_for ?'selected' :null}}>{{$item->interview_for}}</option>
                    
                                            @endforeach
                
                                        </select>
                                    </div>
                                    @if ($errors->has('applicant_interview_for'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('applicant_interview_for') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @php
                                $countries=\App\Country::all();
                            @endphp
    
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <select  name="country" class="form-control country" id='country' >
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}" {{\Session::get("country") == $country->name ?'selected' :null}} >{{$country->name}} </option>
    
                                            @endforeach
                                            
                                        </select>
                                    </div>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 state" style="display: none">
                                <div  class="form-group-material  " >
            
                                    <div class='col-sm-12  mb-12 '>
                                        <select name='state'  class='form-control  states_get'>
                                        </select>
                                        @if ($errors->has('state'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 city" style="display: none">
                                <div  class="form-group-material  " >
            
                                    <div class='col-sm-12  mb-12 '>
                                        <select name='city'  class='form-control  city_get'>
                                        </select>
                                        @if ($errors->has('city'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-3">
                                <div class="form-group-material " >
                                    <div class="">
                                        <select name="sources" class="form-control ">
                                            <option value="">Select Source</option>
                                            <option value="Rozee.pk"{{\Session::get("source") == "Rozee.pk" ?'selected' :null}}>Rozee.pk</option>
                                            <option value="Facebook" {{\Session::get("source") == "Facebook" ?'selected' :null}}>Facebook</option>
                                            <option value="Linkedin" {{\Session::get("source") == "Linkedin" ?'selected' :null}}>Linkedin</option>
                                            <option value="Gmail" {{\Session::get("source") == "Gmail" ?'selected' :null}}>Gmail</option>
                                            <option value="Management" {{\Session::get("source") == "Management" ?'selected' :null}}>Management</option>
                                            <option value="Staff" {{\Session::get("source") == "Staff" ?'selected' :null}}>Staff</option>
                                            <option value="Codility" {{\Session::get("source") == "Codility" ?'selected' :null}}>Codility</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('sources'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('sources') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="bootstrap-iso" >
                                <div class="form-group ">
                                    <label for="expertise_in" class='expertise_in' style="color: #aaa;">Expertise In</label>
                                    <input type="text" value="{{old('expertise_in')}} {{\Session::get("expertise_in")?\Session::get("expertise_in"):null}}" name='expertise_in' data-role="tagsinput" style="    width: 100%;border: none;border-bottom: 1px solid #eee;padding: 10px 0;color: #868e96;font-weight: 300;"/>
                                </div>
                                @if ($errors->has('expertise_in'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('expertise_in') }}</strong>
                                        </span>
                                @endif
                            </div>
                            

                        </div>
                        @if(\Session::get("already_exist") == true)
                            <button type="button" class="btn btn-outline-success submit_first">Submit As New Applicant</button>
    
                        @else
                        <button type="button" class="btn btn-outline-success submit_first">Submit</button>
                        @endif
                    </form>
                    @if(\Session::get("already_exist") == true)
                    <form class="form-inline"  method="POST" action="{{route('reject.applicant')}}">
                        <input  type='hidden' value="reject" name="reject"/>
                        {{ csrf_field() }}
    
                        <button type="submit" class="btn btn-outline-danger ">Reject Applicant</button>

                    </form>
                    @endif
                    <br><br><br>
               @if(\Session::get("already_exist") == true)
                   
                   @php
                   $get_status=\Session::get("duplicate_status");
                   if ($get_status == 'email'){
                                      $duplicate_applicants=\App\Applicants::where('email', \Session::get("email"))->get();
                   }
                   if ($get_status == 'name'){
                   $duplicate_applicants=\App\Applicants::where('firstName', \Session::get("firstName"))->where('LastName', \Session::get("LastName"))->get();
                   }
                   @endphp
                    @if(isset($duplicate_applicants))
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Source</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Area</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($duplicate_applicants)>0)
            
                            @foreach($duplicate_applicants as $applicant)
                                <tr>
                                    <td>{{$applicant->applicantId}}</td>
                                    <td>{{$applicant->date}}</td>
                                    <td>@if($applicant->test()->first() ||  $applicant->interview()->first())
                                            <a href="{{route('get_single_applicant',$applicant->id)}}">{{$applicant->firstName}}{{$applicant->middleName?$applicant->middleName:''}}{{$applicant->LastName?$applicant->LastName:''}}
                                            </a>
                                        @else
                                            {{$applicant->firstName}}{{$applicant->middleName?$applicant->middleName:''}}{{$applicant->LastName?$applicant->LastName:''}}
                        
                                        @endif
                                    </td>
                                    <td>{{$applicant->source?$applicant->source:'No Source Defined'}}</td>
                                    <td>{{$applicant->phoneNumber}}</td>
                                    <td>{{$applicant->email}}</td>
    
                                    <td>{{$applicant->country?$applicant->country.'=>':''}}{{$applicant->city?$applicant->city:''}}</td>
                                    <td>
                                        <a class="cvUploadLink" title="Upload CV" data-id='{{$applicant->id}}'
                                           href='{{url("/upload-cv/{$applicant->id}")}}'><i class="fa fa-upload"></i></a>
                                        &nbsp;
                                        @if($applicant->cvExt=="pdf")
                                            <?php $extClass = "far fa-file-pdf";
                                            $disabled = '';
                                            ?>
                                        @elseif($applicant->cvExt=="doc" || $applicant->cvExt=="docx")
                                            <?php $extClass = "far fa-file-word";
                                            $disabled = '';
                                            ?>
                                        @else
                                            <?php $extClass = "fas fa-eye-slash";
                                            $disabled = 'not-active';
                                            ?>
                                        @endif
                                        <a class="viewCvLink {{$disabled}}" title="View CV"
                                           href='{{url("/view-cv/{$applicant->id}")}}'><i class='{{$extClass}}'></i></a>
                                        @php
                                            $interview_check=$applicant->interview()->orderBy('id','desc')->first();
                                        
                                        @endphp
                                        &nbsp;
                        
                                        @if(isset($interview_check))
                                            @if($interview_check->sub_statusd)
                                                {{--<a title="Edit Interview status" data-target="#editInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-edit"></i></a>--}}
                                                <a title="Edit Interview status" data-value='{{$applicant->id}}' class="edit_link" href="javascript://">
                                                    <span class="fa fa-edit"></span>
                                                </a>
                                            @else
                                                <a title="Add Interview status" data-target="#addInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-plus"></i></a>
                                                &nbsp;
                                            @endif
                                        @else
                                            <a title="Add Interview status" data-target="#addInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-plus"></i></a>
                                            &nbsp;
                                        @endif
                                        <a class="deleteLink" title="Delete" href='{{url("/delete/{$applicant->id}")}}'><i
                                                    class="fa fa-trash-alt"></i></a>
                        
                                        <a title="Add Test Detail" data-target="#addTest" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-file"></i></a>
                    
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @endif
               @endif
                    
                </div>
            
            </div>
        </div>
    </div>
    <div id="createMyModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Manage Status</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id ="timetable" method="POST" action="{{route('status.store')}}" >
                        {{ csrf_field() }}
                    
                        <div class="form-group-material col-md-4" style="position: absolute">
                            <div class='  input-group-material ' >
                                <input autocomplete="off" type='text' id='status' name="status"   value="" class="input-material" required />
                            
                                <label for="attendance" class="label-material col-md-4">Status Title</label>
                            </div>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                            @endif
                        </div>
                        <div class="bootstrap-iso" style="position: relative; float: right;">
                            <div class="form-group ">
                                <label for="sub_status" class="sub_projects">Sub Status</label>
                                <input type="text" value="" name="sub_status" data-role="tagsinput" />
                            </div>
                        </div>
                        <button type="submit" style="position: relative; float: right;" class="btn btn-outline-success clear">Submit</button>
                
                    </form>
            
                </div>
                @if($statuses)
                <div class="modal-footer">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Status Title</th>
                                <th>Sub Statuses </th>
                            </tr>
                            </thead>
                            <tbody>
                            
                                @foreach($statuses as $status)
                    
                                    <tr>
                                        <td>{{$status->status_name}}</td>
                                        <td>
                                            @php
                                                if ($status->sub_status){
                                                $sub_status=$status->sub_status;
                                                }
                                                foreach ($sub_status as $item){
                                                echo $item->name.', ';
                                                }
                                            @endphp
                        
                                        </td>
                                        
                    
                                    </tr>
                                @endforeach
                           
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div id="modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTiltle" class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modalForm" action="/tesing" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div id="modalBody">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submitBtn" type="submit" class="btn btn-outline-success ">Submit</button>
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="addDropDownInterviewFor" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTiltle" class="modal-title">Manage Interview For DropDown</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modalForm" action="{{route('dropdown.store')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="col-md-3">
                            <div class="bootstrap-iso" >
                                <div class="form-group ">
                                    <label for="sub_status" class='add_interview_for'>Add Interview for</label>
                                    <input type="text" value="{{old('add_interview_for')}}" name='add_interview_for' data-role="tagsinput" />
                                </div>
                                @if ($errors->has('add_interview_for'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('add_interview_for') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="addinterviewfordropdown" type="submit" class="btn btn-outline-success ">Submit</button>
                        <button type="button" class="btn " data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="updateInterview" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Interview Update</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body-update-interview">
                </div>
        
            </div>
        </div>
    </div>
    <div id="addInterview" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Interview Details</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id="timetable" method="POST" action="{{route('interview.store')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-material " style="margin: 7px 0 0 0px">
                                    <div class="">
                                        <select name="status_get" class="form-control status_get">
                                            <option value="" >Select Status</option>
                                            @if($statuses)
                                                @foreach($statuses as $status)
                                                    <option value="{{$status->id}}">{{$status->status_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('status_get'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('status_get') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                                <div class="form-group-material date">
                                    <div class=' bootstrap-iso input-group-material date'>
                                        <input autocomplete="off" type='text' id='dates' name="date" value=""
                                               class="input-material"/>
                                    
                                        <label for="date" class="label-material">Interview Date</label>
                                    </div>
                                    @if ($errors->has('date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div  class="form-group-material  view" style="display: none">
                                
                                    <div class='col-sm-12  mb-12 '>
                                        <select name='sub_status'  class='form-control  ajax'>
                                        </select>
                                        @if ($errors->has('sub_status'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('sub_status') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Brief Note</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="description" class="form-control">{{old('description')}}</textarea>
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
            
                </div>
        
            </div>
        </div>
    </div>
    <div id="addTest" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test Details</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id="timetable" method="POST" action="{{route('interviewtest.store')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-material " style="margin: 7px 0 0 0px">
                                    <div class="">
                                        <select name="status" class="form-control status_get">
                                            <option value="" >Select Status</option>
                                            <option value="1" >Pass</option>
                                            <option value="10" >Fail</option>
                                            
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                                <div class="form-group-material ">
                                    <div class=' bootstrap-iso input-group-material'>
                                        <input autocomplete="off" type='file' id='image' name="image"
                                               class="input-material"/>
                                    
                                        <label for="image" class="label-material active">Upload Image</label>
                                    </div>
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='number' id='marks' name="marks" value=""
                                               class="input-material"/>
                
                                        <label for="marks" class="label-material">Obtained Marks</label>
                                    </div>
                                    @if ($errors->has('marks'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('marks') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='serial_number' name="serial_number" value=""
                                               class="input-material"/>
                
                                        <label for="marks" class="label-material">Serial Number</label>
                                    </div>
                                    @if ($errors->has('serial_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('serial_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Brief Note</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="description" class="form-control">{{old('description')}}</textarea>
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
            
                </div>
        
            </div>
        </div>
    </div>
    <div id="addCall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Call Details</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <form class="form-horizontal" id="timetable" method="POST" action="{{route('call_status.store')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            
                            <div class="col-md-3">
                                <div class="form-group-material date">
                                    <div class=' bootstrap-iso input-group-material date'>
                                        <input autocomplete="off" type='text' id='call_date' name="call_date" value="{{old('call_date')}}{{\Session::get("call_date")?\Session::get("call_date"):null}}"
                                               class="input-material"/>
            
                                        <label for="call_date" class="label-material">DateTime of Call</label>
                                    </div>
                                    @if ($errors->has('call_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('call_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                    
                        </div>
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Brief Review On Call</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="description" class="form-control">{{old('description')}}</textarea>
                            </div>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div id="applicant_id_for_call">
    
                        </div>
                        <button type="submit" class="btn btn-outline-success">Submit</button>
                        <button type="button" id="button_clear" class="btn btn-outline-danger">
                            Reset
                        </button>
                    </form>
            
                </div>
        
            </div>
        </div>
    </div>
  
@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('scripts/bootstrap-tagsinput-angular.min.js')}}"></script>
    <script src="{{asset('scripts/bootstrap-tagsinput.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#dob').datetimepicker({
                format: 'L'
            });
            $('#call_date').datetimepicker({
            
            });
            $('#date').datetimepicker({
                format: 'L',

                // minDate:new Date()
            });
            $('#dates').datetimepicker({
            
            });
            $('#date_of_birth').datetimepicker({
                format: 'L'
            });
        });
        $('#button_clear').click(function () {
            $('#timetable input[type="text"]').val('');
            $('#timetable input[type="checkbox"]').prop('checked', false);
        });
        $(document).ready(function () {
            $('.cvUploadLink').on('click', function (event) {
                event.preventDefault();
                var userId = $(this).attr('data-id');
                $('#modalTiltle').html("Upload Resume");
                $('#modalBody').html('<input type="file" name="uploadedCv" accept=".doc, .docx,.pdf"  class="form-control"/><input id="userId" type="hidden" name="userId"/>');
                $('#userId').val(userId);
                $('#modalForm').attr('action', '/upload-cv');
                $('#modal').modal();
            });
            $('.deleteLink').on('click', function (event) {
                event.preventDefault();
                $('#modalTiltle').html("Confirmation");
                $('#modalBody').html("Are you sure you want to delete?");
                $('#modalForm').attr('action', $(this).attr('href'));
                $('#modalForm').attr('method', '');
                $('#modal').modal();
            });
        });
        $(".applicant_id").on('click',function() {
            var applicant_id=$(this).data("value");
            $(".applicant_core_id").html("<input  type='hidden' id='applicant_original_id' style='display:none' name='applicant_original_id' value= "+applicant_id+"    />");
        });
        $(".applicant_id_call").on('click',function() {
            var applicant_id=$(this).data("value");
            $("#applicant_id_for_call").html("<input  type='hidden' id='applicant_original_id' style='display:none' name='applicant_original_id' value= "+applicant_id+"    />");
        });
        $(".status_get").change(function() {
            var status=$(this).val();
                $.get('/get_sub_status/'+ status +'/',function (result) {
                   if(result.length != 0) {
                       // alert('data comming');
                       $(".ajax").html("<option value='' >Select Sub Status</option>");
                       
                       for(var i = 0; i < result.length; i++) {
                           var sub_status = result[i];
                           $(".ajax").append("<option value="+sub_status.id+" >"+sub_status.name+"</option>");
                       }
                       $(".view").css('display', 'block');
                   }
                    if(result.length == 0) {
                        // $(".view").html("asdf");
                        $(".view").css('display','none');
                    }
                   });
        });
        $(".edit_link").on('click',function() {
            var applicantId=$(this).data("value");
            $.get('/interview/'+applicantId+'/edit',function (data) {
                // $.each(data,function (index,state) {
                $('#modal-body-update-interview').append(data);
                $('#updateInterview').modal();
                // $('#modal-body').change(
                
            });

        });
        $(".submit_first").on('click',function() {
            $("#applicantadd").submit()
        });
        @if(isset($errors))
                @if (count($errors) > 0)
                @php
                $check_error=array('The date is not a valid date.','The date field is required.','The dob is not a valid date.','The dob field is required.','The first name field is required.','The gender field is required.','The phone number field is required.','The city field is required.','The country field is required.','The sources field is required.',);
                    $bFound = (count(array_intersect($errors->all(), $check_error))) ? true : false;
                @endphp
                        @if(isset($bFound))
                @if($bFound == true )
                $('#createModal').modal('show');
        
                @endif
                        @endif
        @endif
                        @endif
                @if(isset($errors))
                @if (count($errors) > 0)
                @php
                    $check_error=array('The status field is required.','The image field is required.','The marks field is required.',);
                        $bFounds = (count(array_intersect($errors->all(), $check_error))) ? true : false;
                @endphp
                
                @if(isset($bFounds))
                @if($bFounds == true )
                $('#addTest').modal('show');

        @endif
        @endif
        @endif
        @endif
        @if(isset($errors))
        @if(count($errors) >0 )
                @php
                    $check_error=array('The call date field is required.','The description field is required.','The marks field is required.','The call date is not a valid date.');
                            $call_error = (count(array_intersect($errors->all(), $check_error))) ? true : false;
                @endphp
        @if(isset($call_error))
        @if($call_error == true )
        $('#addCall').modal('show');
        @endif
                @endif
        @endif
        @endif
        @if(\Session::get("already_exist") == true)
                $('#createModal').modal('show');
                        @endif
$(".country").change(function() {
var country=$(this).val();
$.get('/get_state/'+ country +'/',function (result) {
if(result.length != 0) {
// alert('data comming');
$(".states_get").html("<option value='' >Select State</option>");

for(var i = 0; i < result.length; i++) {
    var states = result[i];
    $(".states_get").append("<option value="+states.id+" >"+states.name+"</option>");
}
$(".state").css('display', 'block');
}
if(result.length == 0) {
// $(".view").html("asdf");
$(".state").css('display','none');
}
});
});
$(".states_get").change(function() {
var country=$(this).val();
$.get('/get_city/'+ country +'/',function (result) {
if(result.length != 0) {
// alert('data comming');
$(".city_get").html("<option value='' >Select City</option>");

for(var i = 0; i < result.length; i++) {
    var city = result[i];
    $(".city_get").append("<option value="+city.name+" >"+city.name+"</option>");
}
$(".city").css('display', 'block');
}
if(result.length == 0) {
// $(".view").html("asdf");
$(".city").css('display','none');
}
});
});
</script>
@endsection

