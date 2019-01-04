<?php

namespace App\Mail;

use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
class EmployeeLessTimeConsumed extends Mailable
{
    public $emails;
    public $names;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->names=$item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $employee_emails=$this->emails;
        $user_detail=$this->names;
        $to = array('atta.ur.rehman@codility.co','hussnain.raza@codility.co');


        return $this->markdown('mail_employee_less_time_consumed',compact('user_detail'))->to($to)->subject("Monthly Assessment Alert ! From  ".Carbon::now()->startOfMonth()->format('d-m-Y')."  TO  ".Carbon::now()->subDay(1)->format('d-m-Y'));

//            return $this->markdown('mail_employee_less_time_consumed',compact('user_detail'))->to($user_detail['email'])->subject("Monthly Assessment Alert ! From  ".Carbon::now()->startOfMonth()->format('d-m-Y')."  TO  ".Carbon::now()->format('d-m-Y'));
    }
}
