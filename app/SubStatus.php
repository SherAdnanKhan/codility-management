<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubStatus extends Model
{
    protected $fillable=['name','status_id'];
    public function sub_status(){
        return $this->belongsTo('App\Status');
    }
}
