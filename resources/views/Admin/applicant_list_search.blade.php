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
            <h1 class="h3 display">Applicant Detail</h1>
        </header>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                    
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
                        <th>Applicant Id</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Source</th>
                        <th>Contact</th>
                        <th>Area</th>
                        {{--<th>Gender</th>--}}
                        {{--<th>Age</th>--}}
                        {{--<th>Nationality</th>--}}
                        <th>DOB</th>
                        <th>CurrentSalary</th>
                        <th>ExpectedSalary</th>
                        <th>Interview For</th>
                        <th>Expertise In</th>
                       
                    </tr>
                    </thead>
                    <tbody>
                    @if($applicant != null)
                        {{--@foreach($applicants as $applicant)--}}
                            <tr>
                                <td>{{$applicant->applicantId}}</td>
                                <td>{{$applicant->date}}</td>
                                <td>{{$applicant->firstName}}{{$applicant->middleName?$applicant->middleName:''}}{{$applicant->LastName?$applicant->LastName:''}}</td>
                                <td>{{$applicant->source?$applicant->source:'No Source Defined'}}</td>
                                <td>{{$applicant->phoneNumber}}</td>
                                <td>{{$applicant->country?$applicant->country.'=>':''}}{{$applicant->city?$applicant->city:''}}</td>
                                {{--<td>{{$applicant->gender?$applicant->gender:''}}</td>--}}
                                {{--<td>{{$applicant->gender?$applicant->gender:''}}</td>--}}
                                {{--<td>{{$applicant->age?$applicant->age:''}}</td>--}}
                                {{--<td>{{$applicant->nationality?$applicant->nationality:''}}</td>--}}
                                <td>{{$applicant->dob?$applicant->dob:''}}</td>
                                <td>{{$applicant->currentSalary?$applicant->currentSalary:''}}</td>
                                <td>{{$applicant->expectedSalary?$applicant->expectedSalary:''}}</td>
                                <td>{{$applicant->interview_for?$applicant->interview_for:''}}</td>
                                <td>{{$applicant->expertise_in?$applicant->expertise_in:''}}</td>

                            </tr>
                        {{--@endforeach--}}
                    @endif
                    </tbody>
                </table>
                    <br>
                    <br>
                    <p>Test Logs</p>
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Obtained Marks</th>
                            <th>Status</th>
                            <th>Brief Note</th>
                            <th>Image</th>
                            {{--<th>Contact</th>--}}
                            {{--<th>Area</th>--}}
        
                        </tr>
                        </thead>
                        <tbody>
                        
                        @if($applicant->test)
                            @foreach($applicant->test as $test)
                            <tr>
                                
                                <td>{{$test->marks}}</td>
                                <td>{{$test->status == true?'Pass':'Fail'}}</td>
                                <td>{{$test->note?$test->note:''}}</td>
                                <td> <img src="{{url('/')}}/images/test/{{$test->image != null ?$test->image :'avatar_default.png'}}" alt="No Test Image" class="thumbnail" style="height: 150px;width: 200px;">
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <br>
                    <br>
                    <p>Event Logs</p>
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Status</th>
                            <th>Brief Note</th>
                            <th>Date Of Interview</th>
                            {{--<th>Contact</th>--}}
                            {{--<th>Area</th>--}}
        
                        </tr>
                        </thead>
                        <tbody>
        
                        @if($applicant->interview)
                            @foreach($applicant->interview as $interview_status)
                                <tr>
                                    
                                    <td>{{$interview_status->status ? $interview_status->status->status_name:''}}{{$interview_status->substatus ? '=>'.$interview_status->substatus->name:''}}</td>
                                    <td>{{$interview_status->note?$interview_status->note:''}}</td>
                                    <td>{{$interview_status->date != null ?\Carbon\Carbon::createFromTimestamp($interview_status->date):''}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
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