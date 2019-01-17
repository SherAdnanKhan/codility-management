<?php

namespace App\Helper;

class Helper
{
    public static function all_admins()
    {
        if ( config('app.env') == 'local' ) {
            $all_admins = 'atta.ur.rehman@codility.co';
        }
        else{
            $all_admins=array('amir@codility.co','hr@codility.co','ejaz@codility.co','khurram@codility.co','hussnain.raza@codility.co');
        }
        return $all_admins;
    }


}