<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeTraker extends Model
{
    protected $fillable=['user_id','start_time','url','check_out_time','end_time','check_in_time','slots'];
}
