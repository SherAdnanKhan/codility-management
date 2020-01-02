<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    public function get_admins(){
        return User::whereHas('role', function ($q){
            $q->whereIn('name',['Administrator']);
        });
    }
}
