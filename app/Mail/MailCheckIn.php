<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailCheckIn extends Mailable
{
    public $get_users;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($late_users)
    {
        $this->get_users = $late_users;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $users = $this->get_users;
        return $this->markdown('mail_checkin',compact('users'))->to('iamatta24@gmail.com');
    }
}
