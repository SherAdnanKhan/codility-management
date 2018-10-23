<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
        $to = array('amir@codility.co','hr@codility.co','ejaz@codility.co','khurram@codility.co','hussnain.raza@codility.co');
        return $this->markdown('mail_checkin',compact('users'))->to($to)->subject("Late Employee ".Carbon::now()->format('d-m-Y h:i'));
    }
}
