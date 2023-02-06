<!DOCTYPE html>
<html>
<head>
    <style>
        body{
            font-family: arial, sans-serif;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-top: 0px; margin-bottom: 0px;
        }

        td, th {
            border: 1px solid #000;
            text-align: left;
            padding: 4px;
        }
        @page {
            header: page-header;
            footer: page-footer;
            size: 210mm 297mm;
            margin-top: 1cm;
            margin-bottom: 0cm;
        }
        .dblUnderlined { border-bottom: 3px double; text-align: left }
        h5{
            margin: 0px;
        }
        span{
            width: 10px;
        }

    </style>
</head>
<body>
<p style="margin-left: 68%;"> Serial Number : @php echo (uniqid()); @endphp</p>
<img style="height:100px;width: 100px; float: left; margin: -30px 40px 0px 0px;" class="img-responsive " src="file:///<?= base_path()?>/public/images/favicon.png" alt="{{env('APP_NAME')}}">
<h2 style="float: right" > INTERVIEW TEST</h2>
<h5>To be fill by Candidate :</h5>
<table cellpadding="1" cellspacing="0" border="1">
    <tr>
        <td width="20%" >Candidate Name:</td>
        <td width="30%">&nbsp;</td>
        <td width="20%">Job Post:  </td>
        <td width="30%">&nbsp;</td>

    </tr>
</table>

<table cellpadding="5" cellspacing="0" border="1" >
    <tr>
        <td width="50%">Current Company: &nbsp;</td>
        <td width="50%">Current Salary: &nbsp;</td>

    </tr>
    <tr>
        <td width="50%">Expected Salary: &nbsp;</td>
        <td width="50%">When you Join: &nbsp;</td>

    </tr>
</table>
<h5 >For Office use only :</h5>
<table cellpadding="5" cellspacing="0" border="1">

    <tr>

        <td width="25%">Test start & end time:</td>
        <td width="25%">&nbsp;</td>
        <td width="20%">Test Checked By:  </td>
        <td width="30%">&nbsp;</td>
    </tr>

</table>
@php
    $marks=array();
    $question_number = array();
    $questions_lists=array();

@endphp
<div id="marks">
    <table cellpadding="0" cellspacing="0" border="10" class="up_marks">
        {{--<tr>--}}
            {{--@foreach($category as $itema)--}}
                {{--<td width="50%"style="font-size: 18px"> {{$itema->name}}</td>--}}
            {{--@endforeach--}}
        {{--</tr>--}}
        <tr class="act">
            @php $items_list= array(); @endphp
            @foreach($category as $item)
                @if(isset($all_quantities))
                    @foreach($all_quantities as $quantity => $value)
                        @if($quantity == $item->id)
                            @php
                                $items_lists[]=$item->id;
                                $question=$item->qNA()->where(['category_id'=>$item->id,'proved'=>true])->inRandomOrder()->limit($value)->get();
                                $collection=collect($question);
                                $unique=$collection->uniqueStrict('variation');
                            @endphp
                        @endif
                    @endforeach
                    @php$questions_category= null;
                            $questions_category_array=null;
                    @endphp

                    @foreach($unique->values()->all()  as $print_question )
                        @php
                            $questions_lists[]=$print_question->id;
                            if ($questions_category == $print_question->category_id )
                            {
                                $questions_category_array[]=$questions_category;
                                $marks[]=$print_question->marks;
                            }elseif($questions_category == null ){$marks[]='separator';$marks[]=$print_question->marks;
                            }else{
                                $marks=array();
                            }
                            $questions_category=$print_question->category_id;

                        @endphp
                    @endforeach
                @endif
            @endforeach
            @php
                foreach($items_lists as $item_names){
               $eachecategory_name=\App\QNACategory::whereId($item_names)->pluck('name')->first();
                $get_question=\App\QuestionAnswer::where(['category_id'=>$item_names,'proved'=>true])->whereIn('id',$questions_lists)->pluck('marks')->toArray();
                echo"<td width='50%'style='font-size: 18px'>".$eachecategory_name."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; /".array_sum($get_question)." </td>";
                }
            @endphp
        </tr>
    </table>
</div>
<table cellpadding="5" cellspacing="0" border="1">
    <tr>
        <td width="100%">Remarks &nbsp;</td>

    </tr>
    <tr>
        <td width="100%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="100%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="100%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="100%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="100%"> &nbsp;</td>

    </tr>
</table>


@foreach($items_lists as $item_name)
    @php
        $question_category_name=\App\QNACategory::whereId($item_name)->first();
    @endphp

    <h3 class="dblUnderlined"> {{$question_category_name->name}}</h3>

    @php
        $split_question=array_chunk($questions_lists ,3 ,true)
    @endphp
    <table cellpadding="5" cellspacing="0" border="0" >
    @foreach($questions_lists as $print_get_question)

        @php
            $get_question=\App\QuestionAnswer::where(['id'=>$print_get_question,'category_id'=>$item_name,'proved'=>true])->first();
        @endphp

        @if($get_question != null)
                <tr style="border: 0" >
                    <td width="90%" style="border: 0">{{$get_question?'Q#'.$get_question->id.' : ':''}}{!! html_entity_decode(nl2br(e($get_question?$get_question->question:''))) !!}
                        @if( $get_question->image != null)

                            <img  class="img-responsive " src="file:///<?= base_path()?>/public/images/question/{{$get_question?$get_question->image:''}}" alt="{{env('APP_NAME')}}">
                        @else

                    </td>
                    <td width="10%" style="border: 0 ;text-align: right;">{{$get_question?'('.$get_question->marks.')':''}}</td>
                </tr>
            @endif


        @endif
    @endforeach
    </table>

@endforeach

{{--<htmlpagefooter name="page-footer" >--}}
    {{--<p style="text-align: center;">Codility © {{\Carbon\Carbon::now()->format('Y')}}, All rights reserved</p>--}}
{{--</htmlpagefooter>--}}

</body>
</html>
