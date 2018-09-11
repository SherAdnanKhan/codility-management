@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Upload CSV</title>
@endsection
@section('body')

    <div class="container" style="margin-top: 5%;">
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4><i class="fa fa-file-excel"></i>  Upload CSV File </h4>
                </div>
                <div class="card-body">
    @if(session('info'))
<div class="alert alert-success"> 
{{session('info')}}
</div>
@endif


	<form action="" id="upload-form" enctype="multipart/form-data" method="post">
		<!-- {{csrf_field()}} -->
		<input class="input-material" accept=".xls,.xlsx" type="file"  id="csvFile" name="csvFile" />
        <br>
        <div class="progress" id="progressBarDiv" style="display: none;">
        <div class="progress-bar" id="progressBar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
		<h3 id="status"></h3>
        <!-- <p id="loaded_n_total"></p> To show how many bytes are uploaded -->
	</form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('page_scripts')

    <script>
        $(document).ready(function(){
            $('#csvFile').on('change',function(){


                $("#progressBarDiv").show();
                var file=document.getElementById('csvFile').files[0];
                //alert(file.type);
                //alert(file.name+""+file.size+""+file.type);
                var formData=new FormData();
                formData.append("csvFile",file);
                var ajax=new XMLHttpRequest();
                ajax.upload.addEventListener("progress",progressHandler,false);
                ajax.addEventListener("load",completeHandler,false);
                ajax.addEventListener("error",errorHandler,false);
                ajax.addEventListener("abort",abortHandler,false);
                ajax.onreadystatechange=function(){
                    if (ajax.readyState == 4 && ajax.status == 200) { // when completed we can move away
                        window.location = "/applicants/lists";
                    }
                }
                ajax.open("POST","/upload-csv");
                ajax.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                ajax.send(formData);
            });

            function progressHandler(event)
            {
                $("#loaded_n_total").html("Uploaded: "+event.loaded+" bytes of "+event.total);
                var percent=(event.loaded/event.total)*100;
                $("#progressBar").attr("aria-valuenow",percent);
                $("#progressBar").css("width",percent+"%");
                $("#progressBar").html(Math.round(percent)+"%");
                $("#status").html(Math.round(percent)+"% Uploaded");
            }

            function completeHandler(event)
            {
                $("#status").html=event.target.responseText;
                $("#progressBar").val=0;
            }

            function errorHandler(event)
            {
                $("#status").html="Upload Failed.";
            }

            function abortHandler(event)
            {
                $("#status").html="Upload Aborted.";
            }
        });
    </script>
@endsection