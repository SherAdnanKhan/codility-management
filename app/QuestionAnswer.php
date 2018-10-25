<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
   protected $fillable=['question','answer','category_id','marks'];

   public function category(){
       return $this->hasOne('App\QNACategory','id','category_id');


   }
}
