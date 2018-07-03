<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Leave;
use App\User;
use Carbon\Carbon;
use App\Mail\MailCheckIn as Mails;
use Mail;
use Illuminate\Console\Command;

class MailCheckIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'late:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail to Admins whose are not marked Attendance';

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
    }
}
