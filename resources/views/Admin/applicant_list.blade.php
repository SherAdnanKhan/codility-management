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
                           href="#createMyModal"> <i class="fa fa-cog"></i> Status</a>
                    </div>
                    
                    <div class="col-lg-3">
                        <form method="GET" action="{{route('applicant_list')}}">
                            <div id="custom-search-input">
                                <div class="input-group col-md-12">
                                    <input type="text" class="form-control input-lg" name="query"
                                           placeholder="Search Resume"/>
                                    <span class="input-group-btn">
                                    <button class="btn  btn-lg" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                                <td>{{$applicant->firstName}}{{$applicant->middleName?$applicant->middleName:''}}{{$applicant->LastName?$applicant->LastName:''}}</td>
                                <td>{{$applicant->source?$applicant->source:'No Source Defined'}}</td>
                                <td>{{$applicant->phoneNumber}}</td>
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
                                    &nbsp;
                                    <a title="Add Interview status" data-target="#addInterview" data-toggle="modal" href='#' data-value='{{$applicant->id}}' class="applicant_id"><i class="fa fa-plus"></i></a>
                                    &nbsp;
                                    <a class="deleteLink" title="Delete" href='{{url("/delete/{$applicant->id}")}}'><i
                                                class="fa fa-trash-alt"></i></a></td>
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
                    <form class="form-horizontal" id="timetable" method="POST" action="{{route('applicant.manual')}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group-material ">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='applicantId' name="applicantId"
                                               value="" class="input-material"/>
                                        
                                        <label for="applicantId" class="label-material">Applicant ID</label>
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
                                        <input autocomplete="off" type='text' id='firstName' name="firstName" value=""
                                               class="input-material"/>
                                        
                                        <label for="firstName" class="label-material">Applicant`s First Name</label>
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
                                        <input autocomplete="off" type='text' id='middleName' name="middleName" value=""
                                               class="input-material"/>
                                        
                                        <label for="middleName" class="label-material">Applicant`s Middle Name
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
                                        <input autocomplete="off" type='text' id='lastName' name="lastName" value=""
                                               class="input-material"/>
                                        
                                        <label for="lastName" class="label-material">Applicant`s Last Name </label>
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
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
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
                                        <input autocomplete="off" type='text' id='date' name="date" value=""
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
                                        <input autocomplete="off" type='text' id='dob' name="dob" value=""
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
                                        <input autocomplete="off" type='number' id='age' name="age" value=""
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
                                               value="" class="input-material" placeholder="Pakistani/Indian/American"/>
                                        
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
                                               value="" class="input-material" placeholder="9230000000000"/>
                                        
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
                                               value="" class="input-material"/>
                                        
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
                                               value="" class="input-material"/>
                                        
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
                                        <input autocomplete="off" type='text' id='country' name="country" value=""
                                               class="input-material" placeholder="Pakistan"/>
                                        
                                        <label for="country" class="label-material active">Country</label>
                                    </div>
                                    @if ($errors->has('country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='city' name="city" value=""
                                               class="input-material" placeholder="Lahore"/>
                                        
                                        <label for="city" class="label-material active">City</label>
                                    </div>
                                    @if ($errors->has('city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                           
                            <div class="col-md-3">
                                <div class="form-group-material " style="margin: 7px 0 0 0px">
                                    <div class="">
                                        <select name="source" class="form-control ">
                                            <option value="">Select Source</option>
                                            <option value="Rozee.pk">Rozee.pk</option>
                                            <option value="Facebook">Facebook</option>
                                            <option value="Linkedin">Linkedin</option>
                                            <option value="Gmail">Gmail</option>
                                            <option value="Management">Management</option>
                                            <option value="Staff">Staff</option>

                                        </select>
                                    </div>
                                    @if ($errors->has('source'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('source') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

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
            $('#date').datetimepicker({
                format: 'L',

                // minDate:new Date()
            });
            $('#dates').datetimepicker({
                format: 'L',
                minDate:new Date()
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
    </script>
@endsection