<?php

namespace App;
use App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function isFirstLogin()
    {

            if ($this->firstLogin == true ) {
                return false;
            }
            return true;

    }

}
