<?php

namespace App;
use App\Role;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    public function role()
    {
        return $this->belongsToMany('App\Role','role_admins','user_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'capture_duration',
        'address',
        'qualification',
        'designation',
        'phoneNumber',
        'opening_balance',
        'joiningDate',
        'checkInTime',
        'checkOutTime',
        'breakAllowed',
        'workingDays',
        'abendend',
        'imperative_minutes',
        'compensatory_leaves',
        'allotted_leaves',
        'count_use_leaves',
        'cnic_no',
        'ntn_no',
        'bank_account_no',
        'is_hr',
        'blood_group',
        'shift_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function photos(){
        return $this->hasOne('App\Photo');
    }
    public function request_leave(){
        return $this->hasOne('App\RequestLeave','user_id','id');
    }
    public function users(){
        return $this->hasMany('App\Inform');
    }
    public function informs(){
        return $this->hasOne('App\Inform','user_id','id');
    }
    public function attendance(){

        return $this->hasMany('App\Attendance','user_id','id');
    }
    public function isAdmin()
    {
        foreach ($this->role as $role )
        {
            if ($role->name == 'Administrator') {
                return true;
            }
                return false;
        }
    }

    public function isEmployee()
    {
        foreach ($this->role as $role )
        {
            if ($role->name == 'Employee') {
                return true;
            }
                return false;
        }
    }

    public function isfirstLogin()
    {
        $user = User::whereEmail(Auth::user()->email)->pluck('firstLogin')->first();
        if ($user == true ){
            return false;
        }else {
            return true;
        }
    }
    public function getCheckInTimeAttribute($value)
    {
        return date('h:i A',$value);
    }
    public function getCheckOutTimeAttribute($value)
    {
        return date('h:i A',$value);
    }
    public function tasks(){
        return $this->hasMany('App\Task');
    }
    public function get_tracker_attendance(){

        return $this->hasOne('App\TrackerAttendance','user_id','id');
    }
    public function user_tracker_calculation($date){

        return $this->hasMany('App\TrackerCalculation','user_id','id')->where('date',$date);
    }
    public function user_tracker_status(){

        return $this->hasMany('App\TrackerStatus','user_id','id');
    }
    public function user_tracker_task(){

        return $this->hasMany('App\TrackerTask','user_id','id');
    }
    public function projects(){
        return $this->belongsToMany('App\ProjectTask','projects_users','project_id','user_id');
    }
    public function getCaptureDurationAttribute($value){

        if ($value > 0 ) {
            return $value;
            return Carbon::parse($value)->format('h');
        }
        else{
            return 0;
        }


    }
    public  function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if (!($date->isSunday() || $date->isSaturday())) {

                $dates[] = $date->format('Y-m-d');
            }
        }

        return $dates;
    }
    public  function generateDateRangeWithSunday(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if (!($date->isSunday() )) {

                $dates[] = $date->format('Y-m-d');
            }
        }

        return $dates;
    }
    public function checkHr(){

        if ($this->is_hr == true){
            return true;
        }
        else{
            return false;
        }
    }
}
