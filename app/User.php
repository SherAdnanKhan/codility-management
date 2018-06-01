<?php

namespace App;
use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    public function role()
    {
        return $this->belongsToMany('App\Role','role_admins');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'qualification','designation', 'phoneNumber', 'joiningDate', 'checkInTime', 'checkOutTime', 'breakAllowed', 'workingDays'
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

    public function redirect(){

        if(Auth::user()->isAdmin()){

            return redirect()->route('admin.home');
        }
        else{

            return redirect()->route('employee.home');
        }
    }

}
