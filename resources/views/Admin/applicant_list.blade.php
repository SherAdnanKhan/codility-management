@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Applicants</title>
@endsection
@section('page_styles')
    
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">
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
                           href="#createMyModal">Add Applicant </a>
                        <a  class="btn btn-outline-success"
                           id="MainNavHelp"
                           href="{{route('upload.cvs')}}"><i class="fa fa-upload"></i> Add Bulk Applicants </a>
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
                                <td><a class="cvUploadLink" title="Upload CV" data-id='{{$applicant->id}}'
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
                                    <a title="Edit" href='{{url("/edit/{$applicant->id}")}}'><i class="fa fa-edit"></i></a>
                                    &nbsp;
                                    <a class="deleteLink" title="Delete" href='{{url("/delete/{$applicant->id}")}}'><i
                                                class="fa fa-trash-alt"></i></a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                @if(count($applicants)>0)
                    <div style="display: inline-block;">
                        @if(!isset($query))
                            {{$applicants->links()}}
                        @else
                            {{$applicants->appends(['query' => $query])->links()}}
                        @endif
                    </div>
                    <div>
                        {{$applicants->total()}} Records Found
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
                                <div class="form-group-material">
                                    <div class='input-group-material'>
                                        <input autocomplete="off" type='text' id='source' name="source" value=""
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

@endsection
@section('page_scripts')
    <script src="{{asset('scripts/moment.js')}}"></script>
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#dob').datetimepicker({
                format: 'L'
            });
            $('#date').datetimepicker({
                format: 'L',

                // minDate:new Date()
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
    </script>
@endsection