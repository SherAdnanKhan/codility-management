@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | QuestionAnswers </title>
@endsection
@section('page_styles')
    <style>
        input,
        textarea {
            border: 1px solid #eeeeee;
            box-sizing: border-box;
            margin: 0;
            outline: none;
            padding: 10px;
        }

        input[type="button"] {
            -webkit-appearance: button;
            cursor: pointer;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .input-group {
            clear: both;
            margin: 15px 0;
            position: relative;
        }

        .input-group input[type='button'] {
            background-color: #eeeeee;
            min-width: 38px;
            width: auto;
            transition: all 300ms ease;
        }

        .input-group .button-minus,
        .input-group .button-plus {
            font-weight: bold;
            height: 38px;
            padding: 0;
            width: 38px;
            position: relative;
        }

        .input-group .quantity-field {
            position: relative;
            height: 38px;
            left: -6px;
            text-align: center;
            width: 62px;
            display: inline-block;
            font-size: 13px;
            margin: 0 0 5px;
            resize: vertical;
        }

        .button-plus {
            left: -13px;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            -webkit-appearance: none;
        }
        a.btn.btn-outline-primary.btn-number ,a.btn.btn-outline-danger.btn-number {
            background: white;
        }
        span.fa.fa-minus ,span.fa.fa-plus {
            font-weight: 600;
            font-size: 12px;
        }
        .input-group {
            width: 124px !important;
            /*float: right;*/
            padding: 5px;
            clear: inherit;
            margin-left: 400px;
            margin-top: -12px;
        }
        label.checkbox-inline {
            float: left;
        }

    </style>
@endsection
@section('body')

    <div class="container">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">Print Test</h1>
        </header>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form class="form-horizontal" id="print" method="POST" action="{{route('print.create')}}">
                    <div class="card-header">

                        <div class="row" style="margin-bottom: 30px">
                            <div class="col-lg-3">
                                <button type="submit" class="btn btn-outline-success">Print Test Paper</button>
                            </div>
                            <div class="col-lg-9 ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                
                            </div>

                        </div>
                    </div>
                    <div class="card-body">


                            {{ csrf_field() }}
                            <div class="form-group-material">
                                <label class=" label-material" style="font-weight: lighter">Select Categories</label>
                            </br>

                                    @if($category)

                                        @foreach($category as $get_category)
                                    <label class="checkbox-inline">
                                        @if($get_category->name == 'Algorithms' || $get_category->name == 'Javascript' || $get_category->name == 'Database')
                                            <input id="{{$get_category->name}}" name="print[]"  type="checkbox" value="{{$get_category->id}}" checked > {{$get_category->name}}

                                            @else
                                        <input id="{{$get_category->name}}" name="print[]"  type="checkbox" value="{{$get_category->id}}"> {{$get_category->name}}
                                            @endif
                                    </label>
                                        <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a type="button" class="btn btn-outline-danger btn-number" disabled="disabled" data-type="minus" data-field="quant[{{$get_category->id}}]">
                                                        <span class="fa fa-minus"></span>
                                                    </a>
                                                </span>
                                            <input type="text" name="quant[{{$get_category->id}}]" class="form-control input-number" value="3" min="3" max="10">
                                            <span class="input-group-btn">
                                                    <a type="button" class="btn btn-outline-primary btn-number" data-type="plus" data-field="quant[{{$get_category->id}}]" >
                                                      <span class="fa fa-plus"></span>
                                                    </a>
                                                </span>
                                        </div>
                                        @endforeach

                                    @endif

                                @if ($errors->has('print'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('print') }}</strong>
                                    </span>
                                @endif
                            </div>



                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('page_scripts')
    <script>
        $('input[name="print"]:checked').each(function() {
            alert(this.value);
        });
        $('.btn-number').click(function(e){
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type      = $(this).attr('data-type');
            var input = $("input[name='"+fieldName+"']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                if(type == 'minus') {

                    if(currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if(parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if(type == 'plus') {

                    if(currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if(parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });
        $('.input-number').focusin(function(){
            $(this).data('oldValue', $(this).val());
        });
        $('.input-number').change(function() {

            minValue =  parseInt($(this).attr('min'));
            maxValue =  parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            name = $(this).attr('name');
            if(valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
            } else {
                alert('Sorry, the minimum value was reached');
                $(this).val($(this).data('oldValue'));
            }
            if(valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
            } else {
                alert('Sorry, the maximum value was reached');
                $(this).val($(this).data('oldValue'));
            }


        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    </script>
@endsection
