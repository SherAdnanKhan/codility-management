<?php

namespace App;

use App\User;
class Employee extends User
{
    public static function get_employees($search = false, $select_column = false){
        if ($search && $select_column){
            $employees=User::whereHas('role', function ($q){
                $q->whereIn('name',['Employee']);
            })->where('name','like','%' . $search . '%')->select("$select_column")->get();
            return response()->json([
                'data' => $employees
            ]);
        }elseif($search){
            return User::whereHas('role', function ($q){
                $q->whereIn('name',['Employee']);
            })->where('name','==',$search )->get();
        }else{
            return User::whereHas('role', function ($q){
                $q->whereIn('name',['Employee']);
            })->orderByDesc('id');
        }

    }
}
