<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestInterview extends Model
{
    protected $fillable=[
        'image',
        'marks',
        'status',
        'note'

    ];
}
