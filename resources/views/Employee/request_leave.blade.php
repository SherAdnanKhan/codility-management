@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Manage Time</title>
@endsection
@section('page_styles')

<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">

@endsection
@section('body')
    @if (isset($status))
        <div class="alert alert-success">
            {{ $status }}
        </div>
    @endif
    <div class="container" style="margin-top: 5%;">
        <div class="Register-form" style="background-color: #fff;">
            <div class="form-inner flex-fill">
                <div class="card-header">
                    <h4>Submit Form For Getting Approval </h4>
                </div>

                <div class="card-body">
                    <form class="form-horizontal" id ="Requestleave" method="POST" action="{{ route('request.store') }}">
                    {{ csrf_field() }}
                        <div class="form-group-material">
                            <div class='bootstrap-iso input-group-material date' >
                                    <input autocomplete="off" type='text' id='from_date' name="from_date"   value="" class="input-material" />
                                @if ($errors->has('from_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('from_date') }}</strong>
                                    </span>
                                @endif
                                <label for="from_date" class="label-material">From Date</label>
                            </div>
                        </div>
                        <div class="form-group-material">
                            <div class='bootstrap-iso input-group-material date' >
                                <input autocomplete="off" type='text' id='to_date' value="" name="to_date" class="input-material" />
                                @if ($errors->has('to_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('to_date') }}</strong>
                                    </span>
                                @endif
                                <label for="to_date" class="label-material">To Date</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="reason" class="select-label col-sm-offset-3 col-sm-11 form-control-label ">Describe The Reason</label>
                            <div class="col-sm-12  mb-12 ">
                                <textarea  name="reason" class="form-control">{{old('reason')}}</textarea>
                            </div>
                            @if ($errors->has('reason'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('reason') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-outline-success">Request Submit</button>
                    </form>
                </div>
                </div>
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
            // var FromEndDate = new Date();
            $('#from_date').datetimepicker({format:'L',
                minDate:new Date()
            });
            $('#to_date').datetimepicker({
                
                format:'L',
            });
        });
        $("#from_date").on("dp.change", function (e) {

            $('#to_date').data("DateTimePicker").minDate(e.date);
        });
        // $("#to_date").on("dp.change", function (e) {
        //     $('#from_date').data("DateTimePicker").maxDate(e.date);
        // });
        $("#filter").change(function() {

            if($(this).val() =='custom'){
                $('.date_search').css('display','block');
            }if($(this).val() != 'custom'){
                $('.date_search').css('display','none');
            }

        });
    
    </script>
    <script>
        $(".delete_link").on('click',function() {
            var task=$(this).data("value");
            $.get('/task/'+ task +'/',function (result) {
                $('#modal-body').html("Are You Sure Delete "  +
                    '<form class=form-inline" method="POST"  action ="/inform/'+result.id+'"   enctype="multipart/form-data" >' +
                    '{{method_field('DELETE')}}' +
                    '{{ csrf_field()}}'+
                    ' <button class="form-submit  btn btn-outline-success" type="submit" > Confirm Delete </button> </form>');
                $('#deleteMyModal').modal();
                console.log(result);

            })

        });
    </script>
@endsection