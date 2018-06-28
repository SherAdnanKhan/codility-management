<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable=[
        'user_id',
        'time_take',
        'description'
    ];

    public function getTimeTakeAttribute($value){
        if($value== true) {
            return date('h:i', $value);
        }
        else
        {
            return false;
        }
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

}
