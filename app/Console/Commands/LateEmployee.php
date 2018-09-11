<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Leave;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Mail\MailCheckIn;

class LateEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'late:employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Employee Name whose are not present or Late';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $carbon = Carbon::now();
        $today  = $carbon->startOfDay()->timestamp;
        $late_users=array();

        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('checkInTime','<',Carbon::now()->timestamp)->get();
        foreach ($users as $user)
        {
            $check_attendance = Attendance::whereBetween('check_in_time', [$today, Carbon::now()->timestamp])->where('user_id',$user->id)->first();
            $late_users=array();
            if ($check_attendance== null)
            {
                $late_users = $user->name;

            }
        }

        if (!(empty($late_users))) {
            Mail::send(new MailCheckIn($late_users));
        }
    }
}
