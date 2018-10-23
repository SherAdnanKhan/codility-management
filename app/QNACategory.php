<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QNACategory extends Model
{
    protected $fillable=['name'];


    public function qNA(){
        return $this->hasMany('App\QuestionAnswer','category_id','id');
    }
}
