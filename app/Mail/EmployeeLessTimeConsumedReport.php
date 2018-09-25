<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeLessTimeConsumedReport extends Mailable
{
    public $names;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($names)
    {
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
        return $this->markdown('employee_less_time_consumed_email_report',compact('employee_names'))->to($to);
    }
}
