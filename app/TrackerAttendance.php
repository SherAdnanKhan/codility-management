<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackerAttendance extends Model
{
    protected $fillable=[
        'user_id',
        'check_out_time',
        'check_in_time',
        'date'
    ];

}
