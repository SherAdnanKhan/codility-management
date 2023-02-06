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
    public function status(){
        return $this->hasOne('App\Status','id','status_id');
    }
    public function substatus(){
        return $this->hasOne('App\SubStatus','id','sub_status_id');
    }
    public function applicant(){
        return $this->hasOne('App\Applicants','id','applicant_id');
    }
}
