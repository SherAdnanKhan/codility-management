<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inform extends Model
{
    //

    protected $fillable=[
        'attendance_date',
        'inform_at',
        'user_id',
        'inform_type',
        'reason',
        'inform_late',
        'leave_type'

    ];
    public function users(){
        return $this->belongsTo('App\User','user_id');
    }
    public function leaves(){
        return $this->hasOne('App\Leave','id','leave_type');
    }
    public function attendance(){
        return $this->hasMany('App\Attendance','user_id');
    }
    public function getAttendanceDateAttribute($value){
        return date('m/d/Y ',$value);
    }
    public function getInformAtAttribute($value){
        return date('m/d/Y h:i A',$value);
    }
    public function getInformTypeAttribute($vale){
        return strtoupper($vale);
    }
}
