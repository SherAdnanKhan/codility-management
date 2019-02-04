<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable=[
        'applicant_id',
        'status_id',
        'sub_status_id',
        'date',
        'note',
    ];

}
