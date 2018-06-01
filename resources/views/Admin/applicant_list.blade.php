@extends('layouts.app')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">


                        <div class="row">
                            <div class="col-md-6">
                                All Applicants
                            </div>
                            <div class="col-md-6">
                                <form method="GET" action="{{url('/home')}}">
                                    <div id="custom-search-input">
                                        <div class="input-group col-md-12">
                                            <input type="text" class="form-control input-lg" name="query" placeholder="Search Resume" />
                                            <span class="input-group-btn">
                                    <button class="btn btn-info btn-lg" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>


                    </div>



                    <div class="panel-body">
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
                                <th>First Name</th>
                                <th>Age</th>
                                <th>Contact</th>
                                <th>City</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($applicants)>0)
                                @foreach($applicants->all() as $applicant)
                                    <tr>
                                        <td>{{$applicant->applicantId}}</td>
                                        <td>{{$applicant->date}}</td>
                                        <td>{{$applicant->firstName}}</td>
                                        <td>{{$applicant->age}}</td>
                                        <td>{{$applicant->phoneNumber}}</td>
                                        <td>{{$applicant->city}}</td>
                                        <td><a class="cvUploadLink" title="Upload CV" data-id='{{$applicant->id}}' href='{{url("/upload-cv/{$applicant->id}")}}'><i class="fa fa-upload"></i></a> &nbsp;
                                            @if($applicant->cvExt=="pdf")
                                                <?php $extClass="far fa-file-pdf";
                                                $disabled='';
                                                ?>
                                            @elseif($applicant->cvExt=="doc" || $applicant->cvExt=="docx")
                                                <?php $extClass="far fa-file-word";
                                                $disabled='';
                                                ?>
                                            @else
                                                <?php $extClass="fas fa-ban";
                                                $disabled='not-active';
                                                ?>
                                            @endif
                                            <a  class="viewCvLink {{$disabled}}" title="View CV" href='{{url("/view-cv/{$applicant->id}")}}'><i class='{{$extClass}}'></i></a> &nbsp;
                                            <a title="Edit"  href='{{url("/edit/{$applicant->id}")}}'><i class="fa fa-edit"></i></a> &nbsp;
                                            <a class="deleteLink" title="Delete"  href='{{url("/delete/{$applicant->id}")}}'><i class="fa fa-trash-alt"></i></a></td>
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
                            <div style="display: inline-block;position: absolute;bottom: 73px;right: 30px; color: #3097D1;">
                                {{$applicants->total()}} Records Found
                            </div>

                        @endif
                    </div>
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
                        <div id="modalBody" >

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('myscripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $('.cvUploadLink').on('click',function(event){
                event.preventDefault();
                var userId=$(this).attr('data-id');
                $('#modalTiltle').html("Upload Resume");
                $('#modalBody').html('<input type="file" name="uploadedCv" accept=".doc, .docx,.pdf"  class="form-control"/><input id="userId" type="hidden" name="userId"/>');
                $('#userId').val(userId);
                $('#modalForm').attr('action','/upload-cv');
                $('#modal').modal();
            });

            $('.deleteLink').on('click',function(event){
                event.preventDefault();

                $('#modalTiltle').html("Confirmation");
                $('#modalBody').html("Are you sure you want to delete?");
                $('#modalForm').attr('action',$(this).attr('href'));
                $('#modalForm').attr('method','');
                //$('#submitBtn').remove();
                //`$('.modalFooter').append('<a href="#" class="btn btn-primary">Yes</a>');
                $('#modal').modal();
            });

        });

    </script>
@endsection

@endsection