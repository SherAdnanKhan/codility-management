<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailEmployeeNoNtn extends Mailable
{
    public $employee_detail;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee_detail)
    {
        $this->employee_detail=$employee_detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $employee_detail=$this->employee_detail;
        return $this->markdown('mail_to_employee_no_ntn',compact('employee_detail'))->to($this->employee_detail->email)->subject('Codility Alert !');
    }
}
