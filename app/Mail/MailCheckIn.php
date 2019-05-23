<?php

namespace App\Mail;

use App\Helper\Helper;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class MailCheckIn extends Mailable
{
    public $get_users;
    public $uninforms;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($late_users,$late_user_uninforms)
    {
        $this->get_users = $late_users;
        $this->uninforms=$late_user_uninforms;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $users = $this->get_users;
        $uninforms=$this->uninforms;

        if ((!(empty($users))) && empty($uninforms) ) {
            $get_users_collection = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->whereIn('name', $users)->get();
            $uninform=null;
//            echo ('if  informs');
        }
        if ((!(empty($uninforms))) && empty($users)) {
            $uninform = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->whereIn('name', $uninforms)->get();
            $get_users_collection=null;
//            echo ('if uninforms');

        }
        if ((!(empty($uninforms))) && (!(empty($users)))) {
            $uninform = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->whereIn('name', $uninforms)->get();
            $get_users_collection=User::whereHas('role', function ($q) {
                $q->whereIn('name', ['Employee']);
            })->whereIn('name', $users)->get();
//            echo ('if both');

        }
        $to = Helper::all_admins();
        return $this->markdown('mail_checkin',compact('users' ,'get_users_collection','uninform'))->to($to)->subject("Employees Attendance Status ".Carbon::now()->format('d-m-Y h:i'));
    }
}
