<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestInterview extends Model
{
    protected $fillable=[
        'image',
        'marks',
        'status',
        'note',
        'serial_number',
        'applicant_id'

    ];
    public function applicants(){
        return $this->belongsTo('App\Applicants','applicant_id','id');
    }
}
