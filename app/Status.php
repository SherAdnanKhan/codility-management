<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable=[
        'status_name',
        'is_deleted'

    ];
    public function sub_status(){
        return $this->hasMany('App\SubStatus','status_id','id');
    }
}
