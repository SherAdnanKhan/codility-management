<?php

namespace App\Mail;

use App\Helper\Helper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeeklyReport extends Mailable
{
    public $start_date;
    public $end_date;
    public $names;
    public $total_logged_time;
    public $total_required_time;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_name,$start_date,$end_date)
    {
        $this->start_date=$start_date;
        $this->end_date=$end_date;
        $this->names=$user_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        $employee_names=$this->names;
        $to = Helper::all_admins();
//        $current_month=Carbon::now()->month;
//        $previous_month=Carbon::now()->subDays(6)->month;
//        if($current_month != $previous_month){
//            $start_date_carbon=Carbon::parse($previous_month . '/1');
//            $end_date_carbons=Carbon::parse($previous_month . '/1');
//        }else{
//            $start_date_carbon=Carbon::now();
//            $end_date_carbon=Carbon::now();
//        }
//        if (!(isset($end_date_carbons))) {
//            $date=Carbon::now()->endOfWeek()->subDays(1)->subHours(16)->format('d-m-Y');
//        }else{
//            $date=$end_date_carbons->endOfMonth()->format('d-m-Y');
//        }
//        dd($employee_names);
        return $this->markdown('monthly',compact('employee_names'))->to($to)->subject("Monthly Assessment Report From ".$this->start_date->startOfMonth()->format('d-m-Y')."  TO  ".$this->end_date->format('d-m-Y'));
    }
}
