<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeeklyReport extends Mailable
{
    public $names;
    public $total_logged_time;
    public $total_required_time;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($names)
    {
//        $this->total_logged_time=$total_logged_time;
//        $total_logged_time=$total_required_time;
        $this->names=$names;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $employee_names=$this->names;
        $to = array('amir@codility.co','hr@codility.co','ejaz@codility.co','khurram@codility.co','hussnain.raza@codility.co');
        return $this->markdown('employee_less_time_consumed_email_report',compact('employee_names'))->to($to)->subject("Weekly Report From ".Carbon::now()->startOfWeek()->format('d-m-Y')."  TO  ".Carbon::now()->startOfWeek()->addDays(4)->format('d-m-Y'));
    }
}
