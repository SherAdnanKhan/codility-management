<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    protected $fillable=[
            'applicantId',
            'date',
            'firstName',
            'middleName',
            'lastName',
            'gender',
            'age',
            'nationality',
            'phoneNumber',
            'dob',
            'currentSalary',
            'expectedSalary',
            'city',
            'country'
    ];
    public function experienceAndEducation()
    {
    	return $this->hasMany('App\Education');
    	return $this->hasMany('App\Experience');
    }

}
