<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubProjectTask extends Model
{
    protected $fillable=['name','project_id'];

    public function projects(){
        return $this->belongsTo('App\ProjectTask');
    }
}
