<?php

namespace App\Helper;

use App\User;

class Helper extends User
{
    public static function all_admins()
    {
        if ( config('app.env') == 'local' ) {
            $all_admins = 'hr@codility.co';
        }
        else{
            $all_admins=array('amir@codility.co','ejaz@codility.co','khurram@codility.co','hr@codility.co');
        }
        return $all_admins;
    }

    public static function check_uninformed_late($value){
        if ($value == 'late'){
            return true;
        }else{
            return false;
        }
    }
    public static function check_informed_late($value){
        if ($value == 'inform'){
            return true;
        }else{
            return false;
        }
    }

    public static function check_informed_leave($value){
        if ($value == 'LeaveBySystem'){
            return true;
        }else{
            return false;
        }
    }
    public static function check_uninformed_leave($value){
        if ($value == 'AbsentBySystem'){
            return true;
        }else{
            return false;
        }
    }
    public static function check_leaveby_admin($value){
        if ($value == 'LeaveByAdmin'){
            return true;
        }else{
            return false;
        }
    }
    public static function check_on_time($value){
        if ($value == 'check_in'){
            return true;
        }else{
            return false;
        }
    }

    public static function leave_cotta(){
        $total_months = 12;
        $total_allowed_leaves = 17;
        $per_month_leaves = $total_allowed_leaves / $total_months ;

        return substr($per_month_leaves,0,5);
    }
    public static function get_string_between($string, $start, $end){
//        $string = " ".$string;

        $ini = strpos($string,$start);
        if ($ini == 0) return false;
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }
//    public static function replace_between($string, $start, $end, $replacement) {
//        $ini = strpos($string,$start);
//        if ($ini == 0) return false;
//        $ini += strlen($start);
//        $left_content= substr($string,0,$ini-1 );
//        $len = strpos($string,$end,$ini) - $ini;
//        $right_content= substr($string,$ini+$len+1,strlen($string));
//        return $left_content .$replacement .$right_content;
//    }
    public static function replace_between($string, $start, $end, $replacement,$orignal) {
        $word=$start.$orignal.$end;
        $get_string=str_replace($word, $replacement,$string);
        return $get_string;
    }

}
