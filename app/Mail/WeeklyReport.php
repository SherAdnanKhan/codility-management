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
    public function __construct($user_name)
    {
//        $this->total_logged_time=$total_logged_time;
//        $total_logged_time=$total_required_time;
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
        dd($employee_names);
        $to = array('amir@codility.co','hr@codility.co','ejaz@codility.co','khurram@codility.co','hussnain.raza@codility.co');
        return $this->markdown('monthly',compact('employee_names'))->to($to)->subject("Weekly Report From ".Carbon::now()->startOfMonth()->format('d-m-Y')."  TO  ".Carbon::now()->subDay(1)->format('d-m-Y'));
    }
}
