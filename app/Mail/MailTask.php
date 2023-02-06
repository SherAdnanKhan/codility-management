<?php

namespace App\Mail;

use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailTask extends Mailable
{
    public $attendance;
    public $users;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($check_attendance,$users)
    {
        $this->attendance = $check_attendance;
        $this->users =$users;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $get_attendance = $this->attendance;
        $no_task   = $this->users;
        $to = Helper::all_admins();


        return $this->markdown('mail_task',compact('get_attendance','no_task'))->to($to)->subject('Employee Checkout');
    }
}
