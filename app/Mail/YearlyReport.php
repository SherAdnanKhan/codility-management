<?php

namespace App\Mail;

use App\Helper\Helper;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class YearlyReport extends Mailable
{
    public $user_details;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_details)
    {
        $this->user_details=$user_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user_details=$this->user_details;
        $to = Helper::all_admins();

        return $this->markdown('year',compact('user_details'))->to($to)->subject("Yearly Evaluation Report Till ".Carbon::now()->endOfMonth()->format('d-m-Y'));

    }
}
