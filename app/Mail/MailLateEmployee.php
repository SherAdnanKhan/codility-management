<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailLateEmployee extends Mailable
{
    public $late_users_date;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($late_users_data)
    {
        $this->late_users_date=$late_users_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $email=$this->late_users_date;
        return $this->markdown('mail_late_employee')->to($email);
    }
}
