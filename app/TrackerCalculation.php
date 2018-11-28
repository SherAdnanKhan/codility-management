<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackerCalculation extends Model
{
    protected $fillable=[
        'user_id',
        'time_logged',
        'time_spent',
        'date'
    ];
    public function user_tracker($date){

        return $this->belongsTo('App\TrackerCalculation','user_id','id')->where('date',$date);
    }
}
