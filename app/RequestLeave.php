<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestLeave extends Model
{

    protected $fillable=[
        'to_date',
        'from_date',
        'reason',
        'approved',
        'user_id'
    ];

    public function get_user(){
        return $this->hasOne('App\User','id','user_id');
    }
    public function getApprovedAttribute($value){

        if ($value == true ) {
            return 'Approved ';

        }
        elseif($value == false){
            return 'Not Approved ';
        }


    }
}
