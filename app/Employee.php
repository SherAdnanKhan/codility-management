<?php

namespace App;

use App\User;
class Employee extends User
{
    public  function get_admins(){
        return User::whereHas('role', function ($q){
            $q->whereIn('name',['Employee']);
        });
    }
}
