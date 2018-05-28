<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    public function experienceAndEducation()
    {
    	return $this->hasMany('App\Education');
    	return $this->hasMany('App\Experience');
    }
}
