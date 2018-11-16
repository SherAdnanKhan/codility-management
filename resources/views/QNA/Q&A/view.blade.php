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
        <td>Interview start & end time:</td>
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
<br>
@foreach($category as $item)
<h3> {{$item->name}}</h3>
<br>
<br>
@php        $question=$item->qNA()->where('category_id',$item->id)->inRandomOrder()->get();
$collection=collect($question);
$unique=$collection->uniqueStrict('variation');

@endphp

@foreach($unique->values()->all() as $print_question)
<span style="">Q# {{$print_question->id}} &nbsp; &nbsp;</span><br>{!! html_entity_decode(nl2br(e($print_question->question))) !!} <p style="margin-left: 90%; margin-top: -20px"> ({{$print_question->marks}}) </p>

@if($print_question->image !=null)

<img style="max-width: 90%" class="img-responsive " src="file:///<?= base_path()?>/public/images/question/{{$print_question->image}}" alt="{{env('APP_NAME')}}">
@else

@endif
<br><br><br><br><br><br><br><br><br>
@endforeach
@endforeach

<htmlpagefooter name="page-footer">
     <p style="text-align: center;">Codility © {{\Carbon\Carbon::now()->format('Y')}}, All rights reserved</p>
</htmlpagefooter>
</body>
</html>
