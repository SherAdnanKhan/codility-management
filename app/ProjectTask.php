<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $fillable=[
        'project_name',
        'is_deleted'

    ];
    public function users(){

        return $this->belongsToMany('App\User','projects_users','project_id','user_id');
    }

    public function project_tasks(){
        return $this->hasMany('App\Task','project_id','id');
    }
    public function sub_projects(){
        return $this->hasMany('App\SubProjectTask','project_id','id');
    }
}
