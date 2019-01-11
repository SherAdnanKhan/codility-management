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
    public function get_leaves(){
        return $this->hasOne('App\Leave','id','leave_id');
    }
    public function get_inform_request(){
        return $this->hasMany('App\Inform','request_id','id');
    }
    public function getApprovedAttribute($value){

        if($value =='2'){
            return 'Declined';
        }
        if ($value == true ) {
            return 'Approved ';

        }
        elseif($value == false){
            return 'Not Approved ';
        }


    }

}
