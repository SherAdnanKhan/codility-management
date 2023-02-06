<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackerTask extends Model
{
    protected $fillable=['status_id','task','date'];


    public function trackerTask(){
        return $this->hasMany('App\TrackerStatus','task_id','id');
    }
}
