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
            'LastName',
            'gender',
            'age',
            'nationality',
            'phoneNumber',
            'dob',
            'currentSalary',
            'expectedSalary',
            'city',
            'source',
            'country',
            'interview_for',
            'email',
            'expertise_in'
    ];
    public function experienceAndEducation()
    {
    	return $this->hasMany('App\Education');
    	return $this->hasMany('App\Experience');
    }
    public function test(){
        return $this->hasMany('App\TestInterview','applicant_id','id');
    }
    public function interview(){
        return $this->hasMany('App\Interview','applicant_id','id');
    }
    public function call_statuses(){

        return $this->hasMany('App\CallStatus','applicant_id','id');
    }
    public function interview_for_get(){
        return $this->hasOne('App\DropDown','id','interview_for');
    }
}
