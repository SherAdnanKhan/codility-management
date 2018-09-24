<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailReport extends Mailable
{
    public $get_attendances;
    public $users;
    public $tasks;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($get_attendance, $users, $tasks)
    {
        $this->tasks = $tasks;
        $this->users = $users;
        $this->get_attendances = $get_attendance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $report_tasks = $this->tasks;
        $report_attendance = $this->get_attendances;
        $report_users = $this->users;
        $to = array('amir@codility.co','hr@codility.co','ejaz@codility.co','khurram@codility.co','hussnain.raza@codility.co');

        return $this->markdown('mail_report', compact('report_attendance', 'report_tasks', 'report_users'))->to('atta.ur.rehman@codility.co');


    }
}