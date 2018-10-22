<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
class EmployeeLessTimeConsumed extends Mailable
{
    public $emails;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emails)
    {
        $this->emails=$emails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $employee_emails=$this->emails;
        return $this->markdown('mail_employee_less_time_consumed')->to($employee_emails)->subject("Weekly Evaluation Alert ! From  ".Carbon::now()->startOfWeek()->format('d-m-Y')."  TO  ".Carbon::now()->startOfWeek()->addDays(4)->format('d-m-Y'));
    }
}
