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
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        @page {
            header: page-header;
            footer: page-footer;
            size: 210mm 297mm;
            margin-top: 1cm;
            margin-bottom: 5cm;
        }
        .dblUnderlined { border-bottom: 3px double; text-align: center }
    </style>
</head>
<body>
<p style="margin-left: 68%;"> Serial Number : @php echo (uniqid()); @endphp</p>
<img style="height:100px;width: 100px; float: left; margin: -30px 40px 0px 0px;" class="img-responsive " src="file:///<?= base_path()?>/public/images/favicon.png" alt="{{env('APP_NAME')}}">
<h2 style="float: right" > INTERVIEW TEST</h2>
<h5>To be fill by Candidate :</h5>

<table cellpadding="5" cellspacing="0" border="1">
    <tr>
        <td width="20%">Candidate Name:</td>
        <td width="30%">&nbsp;</td>
        <td width="20%">Job Post:  </td>
        <td width="30%">&nbsp;</td>

    </tr>



</table>
<br>
<table cellpadding="5" cellspacing="0" border="1">
    <tr>
        <td width="50%">Current Company: &nbsp;</td>
        <td width="50%">Current Salary: &nbsp;</td>

    </tr>
    <tr>
        <td width="50%">Expected Salary: &nbsp;</td>
        <td width="50%">When you Join: &nbsp;</td>

    </tr>
</table>
<br>
<h5>For Office use only :</h5>
<table cellpadding="5" cellspacing="0" border="1">

    <tr>

        <td width="20%">Test start & end time:</td>
        <td width="30%">&nbsp;</td>
        <td width="20%">Test Checked By:  </td>
        <td width="30%">&nbsp;</td>
    </tr>

</table>
<br>
@php
    $marks=array();
    $question_number = array();
    $questions_lists=array();

@endphp
<div id="marks">
    <table cellpadding="0" cellspacing="0" border="10" class="up_marks">
        <tr>
            @foreach($category as $itema)
                <td width="50%"style="font-size: 18px"> {{$itema->name}}</td>
            @endforeach
        </tr>
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
                $get_question=\App\QuestionAnswer::where(['category_id'=>$item_names,'proved'=>true])->whereIn('id',$questions_lists)->pluck('marks')->toArray();
                echo"<td width='50%'style='font-size: 18px'>Marks : ".array_sum($get_question)." </td>";
                }
            @endphp
        </tr>
    </table>
</div>
<br>

<table cellpadding="5" cellspacing="0" border="1">
    <tr>
        <td width="40%">Remarks By &nbsp;</td>
        <td width="60%">Remarks &nbsp;</td>

    </tr>
    <tr>
        <td width="40%"> &nbsp;</td>
        <td width="60%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="40%"> &nbsp;</td>
        <td width="60%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="40%"> &nbsp;</td>
        <td width="60%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="40%"> &nbsp;</td>
        <td width="60%"> &nbsp;</td>

    </tr>
    <tr>
        <td width="40%"> &nbsp;</td>
        <td width="60%"> &nbsp;</td>

    </tr>
</table>
<br>
<br>


@foreach($items_lists as $item_name)
    @php
        $question_category_name=\App\QNACategory::whereId($item_name)->first();
    @endphp

    <h3 class="dblUnderlined"> {{$question_category_name->name}}</h3>
    <br>

    @php
        $split_question=array_chunk($questions_lists ,3 ,true)
    @endphp
    @foreach($questions_lists as $print_get_question)
        <br>
        @php
            $get_question=\App\QuestionAnswer::where(['id'=>$print_get_question,'category_id'=>$item_name,'proved'=>true])->first();
        @endphp
        @if($get_question != null)
            <span style=""> {{$get_question?'Q#'.$get_question->id:''}} &nbsp; &nbsp;</span><p style="margin-left: 90%; margin-top: -50px; "> {{$get_question?'('.$get_question->marks.')':''}} </p><br>{!! html_entity_decode(nl2br(e($get_question?$get_question->question:''))) !!}

            @if( $get_question->image != null)

                <img  class="img-responsive " src="file:///<?= base_path()?>/public/images/question/{{$get_question?$get_question->image:''}}" alt="{{env('APP_NAME')}}">
            @else

            @endif

            <br><br><br><br><br><br><br><br><br>
        @endif
    @endforeach

@endforeach

<htmlpagefooter name="page-footer">
    <p style="text-align: center;">Codility © {{\Carbon\Carbon::now()->format('Y')}}, All rights reserved</p>
</htmlpagefooter>

</body>
</html>
