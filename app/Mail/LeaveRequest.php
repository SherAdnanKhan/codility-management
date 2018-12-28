<?php

namespace App\Mail;

use App\Helper\Helper;
use Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequest extends Mailable
{
    public $get_request;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($leave_request)
    {
        $this->get_request = $leave_request;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $to = Helper::all_admins();
            $get_leave_request=$this->get_request;

            return $this->markdown('mail_leave_request',compact('get_leave_request'))->to($to)->subject("Request For Leave Approval". \Carbon\Carbon::now()->format('d-m-Y h:i'));
    }
}
