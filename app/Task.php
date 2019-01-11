<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable=[
        'user_id',
        'time_take',
        'description',
        'date',
        'project_id','sub_project'
    ];

    public function getTimeTakeAttribute($value){
        if($value== true) {
            return date('H:i', $value);
        }
        else
        {
            return false;
        }
    }
    public function getDateAttribute($value){
        if($value== true) {
            return date('m/d/Y', $value);
        }
        else
        {
            return false;
        }
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function attendance(){
        return $this->hasMany('App\Attendance','user_id','user_id');
    }
    public function projects(){
        return $this->hasOne('App\ProjectTask','id','project_id');
    }
    public function sub_projects(){
        return $this->hasOne('App\SubProjectTask','id','sub_project');
    }

}
