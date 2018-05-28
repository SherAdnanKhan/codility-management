<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table='education';
    public function applicant()
    {
    	return $this->belongsTo('App\Applicant');
    }
}
