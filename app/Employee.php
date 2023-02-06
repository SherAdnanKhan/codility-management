<?php

namespace App;

use App\User;
class Employee extends User
{
    protected $table = 'users';
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(function ($query) {
            $query->whereHas('role', function ($q){
                $q->whereIn('name',['Employee']);
            });
        });
    }
}
