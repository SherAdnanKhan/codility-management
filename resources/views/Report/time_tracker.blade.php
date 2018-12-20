@extends('layouts.app')
@section('title')
    <title> {{config('app.name')}} | Inaccuracy Report </title>
@endsection
@section('page_styles')
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    {{--<link rel="stylesheet" href="{{asset('/styles/bootstrap-datetimepicker.min.css')}}">--}}
@endsection
@section('body')
    <div class="container-fluid" style="padding: 0rem;">
        <!-- Page Header-->
        <header class="page-header">
            <h1 class="h3 display">{{\Auth::user()->name.'`s'}} Time Tracker Report</h1>
        </header>
        @if(!empty($status_error))
            <div class="alert alert-success hidden-print">
                {{ $status_error }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header slide">
                        <div class="row" >
                            <div class="col-md-8 " style="margin-top: 10px">
                                <form class="time_tracker_search" id ="time_tracker_search" method="POST" action="{{route('time.tracking.search')}}" >
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <button class="paginate left previous" value="previous"><i></i><i></i></button>
                                            <input type="hidden"  name="date" value="{{isset($date_coming)?\Carbon\Carbon::createFromTimestamp($date_coming)->toDayDateTimeString():\Carbon\Carbon::now()->toDayDateTimeString()}}">
                                            <input type="hidden" name="check" class="check"  value="">
                                            <div class="date">
                                                <h4>
                                                    {{isset($date_coming)?\Carbon\Carbon::createFromTimestamp($date_coming)->toFormattedDateString():\Carbon\Carbon::now()->toFormattedDateString()}}
                                                </h4></div>
                                            @if(\Carbon\Carbon::createFromTimestamp($date_coming)->isToday())

                                            @else
                                                <button class="paginate right next" value="next"><i></i><i></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            {{--<div class="col-md-4">--}}
                            {{--</div>--}}
                            <div class="task_description_margin" class="col-md-2" style="text-align: left;    margin-top: 20px;">
                                <table cellpadding="1" cellspacing="0" width="100%">
                                    <tr>
                                        <td colspan="3">Status</td>
                                    </tr>
                                    <tr>
                                        <td width="60%">Off</td>
                                        <td width="5%">:</td>
                                        <td width="35%"><span class="status-off"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Default</td>
                                        <td>:</td>
                                        <td><span class="status-default"></span></td>
                                    </tr>
                                    <tr>
                                        <td>ON</td>
                                        <td>:</td>
                                        <td><span class="status-on"></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="">
                        @if((isset($status)))
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Total Time Logged : {{isset($calculation)?(sprintf("%02d:%02d", floor($calculation->time_logged/60), $calculation->time_logged%60)):''}}</h4>
                                </div>
                                <div class="col-md-4">
                                    <h4>Total Time Spent : {{isset($calculation)?(sprintf("%02d:%02d", floor($calculation->time_spent/60), $calculation->time_spent%60)):''}}</h4>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <?php
                            $i	=	 '';
                            $previous_task='';
                            $previous_report_start_time=null;
                            $start_length=0;
                            $end_length=15;
                            $total_loop	=	'50';$allow_char	=	'15';
                            $previous_report_actual_time=null;
                            $user=\App\User::whereId(\Auth::user()->id)->first();
                            $user_screen_capture_duration=$user->capture_duration;

                            ?>
                            @foreach($status as $tracker_status)

                                @php

                            $next_report_start_time=\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->startOfHour();
                            if ($previous_report_start_time  != null)
                            {
                            $check_diff_in_hours=$previous_report_start_time->diffInHours($next_report_start_time);
                                if ($check_diff_in_hours == true )
                                {
                                echo "<div class='clear'></div>";
                                $ad=1;
                                }
                            }
                            $task_status_get=\App\TrackerStatus::whereBetween('report_start_time', [\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->startOfHour()->timestamp, \Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->endOfHour()->timestamp])->first();
                            if ($task_status_get->id == $tracker_status->id)
                            {
                            echo "<div class='time_tracker_timing'>" . str_replace(' ','<br />', \Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->startOfHour()->format('h A')) . "</div>";
                            $get_actual_next_time=\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->format('i');
                                if ($get_actual_next_time >= ($user_screen_capture_duration))
                                {
                                $get_start_how_div_empty=intval($get_actual_next_time / ($user_screen_capture_duration));
                                    while($get_start_how_div_empty > 0)
                                    {
                                    echo "<div class= ' image_box empty_screen' style='display: inline-block; width: 160px; color: #fff; '><div class='screen_img' ></div></div>";
                                    $get_start_how_div_empty --;
                                    }
                                }
                            }
                            $ad='';
                            if ($previous_report_start_time  != null)
                            {
                            $user_screen_capture_duration=$user->capture_duration;
                             $check_diff_in_hours=$previous_report_start_time->equalTo($next_report_start_time);
                            
                             $add_time_for_add_time_to_start_report_time=$previous_report_actual_time->addMinute($user_screen_capture_duration);
                            $next_report_actual_time=\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time);
                            $get_diff_in_time=$next_report_actual_time->diffInMinutes($add_time_for_add_time_to_start_report_time);
                             $get_total_null_screen=intval(abs($get_diff_in_time / ($user_screen_capture_duration)));
                             $get_screen=$get_total_null_screen;
                        if ($check_diff_in_hours == true ){
                            if ($previous_report_actual_time != null){

                                    $check=1;
                                    if (!($next_report_actual_time->greaterThanOrEqualTo($add_time_for_add_time_to_start_report_time) && $next_report_actual_time->lessThan(\Carbon\Carbon::parse($add_time_for_add_time_to_start_report_time)->addMinute($user_screen_capture_duration)->subMinute(4)))){
                                            while($get_total_null_screen > 0){
                                            echo "<div class= 'image_box empty_screen' style='display: inline-block; width: 160px; color: #fff; '><div class='screen_img' ></div></div>";
                                            $get_total_null_screen --;
                                            }
                                            }
                                    }
                                    }else{
                                
                                    }
                                    $start_hour= Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->startOfHour();
                                    $last_task=$previous_report_actual_time;
                                    if ($start_hour->greaterThan($last_task)){
                                
                                    $get_last_scren_time= $add_time_for_add_time_to_start_report_time->format('i');
                                    if ($get_last_scren_time >= ($user_screen_capture_duration) ){
                                    $get_remaining_hours=60 - $get_last_scren_time;
                                    $get_required_screen=intval($get_remaining_hours / ($user_screen_capture_duration));
                                }
                                    }
                                
                                    }
                                    $get_total_null_screens=0;
                                @endphp



                                <div class="image_box"style="display: inline-block; width: 160px; color: #fff; ">

                                    <div class="task_block">
                                        @if($tracker_status->status_tracker_task->task == null )
                                            <div class="default_task_show single_task_show_null" >
                                                &nbsp;
                                            </div>
                                        @endif
                                        @if($tracker_status->task_id != null )
                                            @php
                                                $task_status_get=$tracker_status->status_tracker_task;
                                                $last_status_get=$task_status_get->trackerTask()->orderBy('id','desc')->get()->first()->id;
                                                $task_status_echo=$task_status_get->trackerTask->count();
                                                $first_status_check=$task_status_get->trackerTask->first()->id;
                                                if ($task_status_echo > 1){
                                                $task_status_show_first_time=substr($tracker_status->status_tracker_task->task,$start_length,$end_length);
                                                }
                                            @endphp
                                            @if($tracker_status->status_tracker_task->id != $previous_task)
                                                @php
                                                    $i	=	 '';
                                                @endphp
                                            @endif
                                            @if($tracker_status->status_tracker_task->id == $previous_task && $tracker_status->status_tracker_task->task != null)
                                                <div  class="task_description_margin default_task_show <?= ($last_status_get == $tracker_status->id)? 'end_task_show':''?>">
                                                    @php
                                                        $i++;

                                                          echo(substr($tracker_status->status_tracker_task->task, $allow_char*$i, $allow_char));
                                                    @endphp
                                                </div>
                                            @else
                                                    @if($tracker_status->status_tracker_task->task != null )
                                                <div class="default_task_show <?= ($task_status_echo > 1 ? ' start_task_show ':'') . ($task_status_echo == 1 ? 'single_task_show':'');?>  ">
                                                        {{$tracker_status->status_tracker_task ? ( $task_status_echo > 1 ?$task_status_show_first_time:substr($tracker_status->status_tracker_task->task,$start_length,$end_length-3).'..') : ""}}
                                                           &nbsp;
                                                </div>
                                                        @endif
                                            @endif
                                            @php
                                                $previous_task=$tracker_status->status_tracker_task->id;
                                            @endphp
                                        @else
                                        @endif
                                        <div class="card-bodys">
                                            <div class="screen_img">
                                            @if($tracker_status->url != null)
                                                <img  src="{{$tracker_status->url}}" />
                                            @else

                                            @endif
                                            <div class="clear"></div>
                                            @if($tracker_status->status == 'OFF')
                                                <div class="layer-off"></div>
                                            @elseif($tracker_status->status == 'DEFAULT')
                                                <div class="layer-default"></div>
                                            @else
                                                <div class="layer-on"></div>
                                            @endif

                                            </div>
                                        </div>
                                        <div class="card-footers">
                                            <p class="tracker_time">{{\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->format('h:i A')}}</p>
                                            <div class="time_tracker_bits">
                                                @foreach($tracker_status->status_tracker_detail as $tracker_detail_bits)
                                                    @if($tracker_detail_bits->status == 'DEFAULT')
                                                        <span class="bit-default"></span>
                                                    @elseif($tracker_detail_bits->status == false)
                                                        <span class="bit-off"></span>
                                                    @else($tracker_detail_bits->status == 1)
                                                        <span class="bit-on"></span>
                                                    @endif

                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    if (isset($get_required_screen)){
                                       while($get_required_screen > 0){
                                    if ($get_required_screen == 1 ){
                                       echo "<div class= 'set_up image_box empty_screen' style='display: inline-block; width: 160px; color: #fff; '><div class='screen_img' ></div></div>";
                                   
                                        }else{
                                         echo "<div class= 'set_up image_box empty_screen' style='display: inline-block; width: 160px; color: #fff; margin-left:".'160'* ($get_required_screen -1).'px'." '><div class='screen_img' ></div></div>";
                                      }
                                        $get_required_screen --;
                                      }
                                     }
                                   $previous_report_start_time=\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->startOfHour();
                                      if ($previous_report_start_time->startOfHour()){
                                    $previous_report_start_time=\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time)->startOfHour();
                                   
                                       }
                                        $previous_report_actual_time=\Carbon\Carbon::createFromTimestamp($tracker_status->report_start_time);
                                @endphp
                            @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('page_scripts')
    <script>
        // $(document).ready(function(e){var nav_width=$('.mCustomScrollbar').width();var header_height=$('header.header').height()+10;$('body').append('<div style="width:19px; height:100vh; background:#fff; position:fixed; top:' + header_height + 'px; left:' + nav_width + 'px; z-index:34;">&nbsp;</div>'); });
    </script>
    <script>
        $(function(){
            $('.previous').click(function () {
                $('.check').val('previous');
                $( "#time_tracker_search" ).submit();
            });
            $('.next').click(function () {
                $('.check').val('next');
                $( "#time_tracker_search" ).submit();
            });
        });
    </script>
    <script src="{{asset('scripts/moment.js')}}"></script>
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>--}}
    <script src="{{asset('scripts/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            // var FromEndDate = new Date();
            $('#month').datetimepicker({format:'Y/M'
            });
        });
    </script>
@endsection
