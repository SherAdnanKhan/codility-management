<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class AllottedLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allotted:leaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will allotted leaves to all employees';

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
        $get_user = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        foreach ($get_user as $user) {
            $compensatory_leaves=$user->compensatory_leaves;
            $update_leaves=$user->update(['allotted_leaves'=> 17,'compensatory_leaves'=>$compensatory_leaves?$compensatory_leaves:0]);
        }
    }
}
