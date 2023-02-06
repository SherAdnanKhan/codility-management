<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable=[
        'header',
        'body',
        'footer'
    ];
    public function getHeaderAttribute($value){
        if ($value == null){
            return 'Email To Applicant';
        }else{
            return $value;
        }
    }
}
