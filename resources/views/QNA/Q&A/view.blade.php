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
        htmlpagefooter{
        
        }
    </style>
</head>
<body>
        <img style="height:100px;width: 100px; float: left; margin: -30px 40px 0px 0px;" class="img-responsive " src="file:///<?= base_path()?>/public/images/favicon.png" alt="{{env('APP_NAME')}}">
    <h2 style="float: right" > INTERVIEW TEST</h2>

        <table cellpadding="5" cellspacing="0" border="1">
            <tr>
                <td width="20%">Candidate Name:</td>
                <td width="30%">&nbsp;</td>
                <td width="20%">Date Of Interview:</td>
                <td width="30%">&nbsp;</td>
            
            </tr>
            <tr>
                <td>Test start & end time:</td>
                <td>&nbsp;</td>
                <td>Test Checked By:</td>
                <td>&nbsp;</td>
            
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
                        
                        @php
                            $items_lists[]=$item->id;
                            $question=$item->qNA()->where(['category_id'=>$item->id,'proved'=>true])->inRandomOrder()->limit(3)->get();
                            $collection=collect($question);
                            $unique=$collection->uniqueStrict('variation');
                        @endphp
                            
                            @foreach($unique->values()->all() as $print_question)
                                @php $questions_lists[]=$print_question->id;
                                    
                                    $marks[]=isset($print_question->marks) ? $print_question->marks:0;
                                @endphp
                            @endforeach
                    @endforeach
                        @php
                            $split=array_chunk($marks ,3,true);
                                foreach ($split as $marks_array) {
                                    echo"<td width='50%'style='font-size: 18px'>Marks : ".array_sum($marks_array)." </td>";
                                    }
                        @endphp
                </tr>
            </table>
        </div>
    <br>
    <br>
    <br>

            @foreach($items_lists as $item_name)
                @php
                    $question_category_name=\App\QNACategory::whereId($item_name)->first();
                @endphp
                
                <h3> {{$question_category_name->name}}</h3>
    <br>
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
