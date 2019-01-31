@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Upload CSV</title>
@endsection
@section('body')
    
    <div class="container" style="margin-top: 5%;">
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4><i class="fa fa-file-excel"></i> Upload CSV File </h4>
                </div>
                <div class="card-body">
                    @if(session('info'))
                        <div class="alert alert-success">
                            {{session('info')}}
                        </div>
                    @endif
                    
                    <form id="upload_form" enctype="multipart/form-data" method="post">
                        <input type="file" name="csvFile" id="csvFile" accept=".xls,.xlsx" onchange="uploadFile()"><br>
                        <div class="progress" id="progressBarDiv" style="background-color: white">
                            <progress class="progress-bar" id="progressBar" role="progressbar"></progress>
                        </div>
                        <p id="status"></p>
                        <p id="loaded_n_total"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('page_scripts')
 <script type="text/javascript">
        function _(el) {
            return document.getElementById(el);
        }

        function uploadFile() {
            var file = _("csvFile").files[0];
            // alert(file.name+" | "+file.size+" | "+file.type);
            var formdata = new FormData();
            formdata.append("csvFile", file);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);

            ajax.open("POST", "/upload-csvss");

            ajax.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            ajax.send(formdata);
        }

        function progressHandler(event) {
            _("loaded_n_total").innerHTML = "Uploaded " + event.loaded + " bytes of " + event.total;
            var percent = (event.loaded / event.total) * 100;
            _("progressBar").value = Math.round(percent);
            _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
        }

        function completeHandler(event) {
            $("#status").html(event.target.responseText);
            _("progressBar").value = 100; //wil clear progress bar after successful upload
        }
    </script>

@endsection
