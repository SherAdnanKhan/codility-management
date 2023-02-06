<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplicantEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $content;
    public $applicant;
    public $email_header;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($replace,$applicant,$email_header)
    {
        $this->content=$replace;
        $this->applicant=$applicant;
        $this->email_header=$email_header;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content=$this->content;
        $header=$this->email_header;
        $applicant_detail=$this->applicant;
        return $this->markdown('mail_applicant_interview',compact('content','applicant_detail'))->to(['amir@codility.co','hr@codility.co','ejaz@codility.co'])->subject("$this->email_header");

    }
}
