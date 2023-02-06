<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallStatus extends Model
{
   protected $fillable=[
       'applicant_id',
       'description',
       'date'
   ];

   public function applicants(){
       return $this->belongsTo('App\Applicants','id','applicant_id');
   }

}
