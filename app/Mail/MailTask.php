<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailTask extends Mailable
{
    public $task;
    public $users;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($get_task,$users)
    {
        $this->task = $get_task;
        $this->users =$users;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $get_tasks = $this->task;
        $no_task   = $this->users;
        $to = array('amir@codility.co','hr@codility.co','ejaz@codility.co','khurram@codility.co');
        return $this->markdown('mail_task',compact('get_tasks','no_task'))->to($to);
    }
}
