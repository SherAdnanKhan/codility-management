<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //

    protected $fillable=['name','color_code','allowed','leave_type','public_holiday'];
}
