<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Inform;
use App\Leave;
use App\Mail\MailLateEmployee;
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
        $late_users_data=array();
        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->whereBetween('checkInTime',[Carbon::now()->subMinutes(35)->timestamp, Carbon::now()->timestamp])->get();
//        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->whereBetween('checkInTime',[$today, Carbon::now()->timestamp])->get();
        foreach ($users as $user)
        {
            $check_attendance = $user->attendance()->whereBetween('check_in_time', [$today, Carbon::now()->timestamp])->where('user_id',$user->id)->first();

            $informs=$user->informs()->whereBetween('attendance_date', [$today, Carbon::now()->timestamp])->first();
            if ($check_attendance== null && $informs == null)
            {
                $late_users[] = $user->name;
                $late_users_data[]=$user->email;
            }
        }
        if (!(empty($late_users) && empty($late_users_data))) {
            Mail::send(new MailLateEmployee($late_users_data));
            Mail::send(new MailCheckIn($late_users));
        }
    }
}
