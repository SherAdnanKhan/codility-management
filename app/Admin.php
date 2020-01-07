<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    protected $table = 'users';
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(function ($query) {
            $query->whereHas('role', function ($q){
                $q->whereIn('name',['Administrator']);
            });
        });
    }
}
