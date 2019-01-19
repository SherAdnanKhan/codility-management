<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeRequestApproved extends Mailable
{
    public $request_leave;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request_leaves)
    {
        $this->request_leave=$request_leaves;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user=$this->request_leave->get_user;
        $to =$user->email;
        $get_leave_request=$this->request_leave;

        return $this->markdown('mail_employee_leave_request',compact('get_leave_request','user'))->to($to)->subject("Leave Request Decline/Approved ". \Carbon\Carbon::now()->format('d-m-Y h:i'));

    }
}
