<?php

namespace App\Http\Controllers\Auth;

use App\Role;
use App\TimeTable;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'                  => 'required|string|max:255',
            'address'               => 'required|string|max:255',
            'qualification'         => 'required|string|max:255',
            'phoneNumber'           => 'required|string|max:255',
            'joiningDate'           => 'required|date|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:6',
            'confirm_password'      => 'required|same:password',
            'designation'           => 'required',
            'cnic'                  => 'integer',
            'ntn'                   => 'integer',
            'account_no'            => 'integer'


        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function getAllowedDays()
    {
        $get_time  =TimeTable::whereId(1)->first();
        $check_day['monday']        = $get_time->monday;
        $check_day['tuesday']       = $get_time->tuesday;
        $check_day['wednesday']     = $get_time->wednesday;
        $check_day['thursday']      = $get_time->thursday;
        $check_day['friday']        = $get_time->friday;
        $check_day['saturday']      = $get_time->saturday;
        $check_day['sunday']        = $get_time->sunday;
        $time                       = array_count_values($check_day);
        return $time[1];
    }
    protected function create(array $data)
    {

        $count =$this->getAllowedDays();
        $timing  = TimeTable::whereId(1)->first();
        $user = User::create([
            'name'                  => $data['name'],
            'email'                 => $data['email'],
            'password'              => bcrypt($data['password']),
            'address'               => $data['address'],
            'qualification'         => $data['qualification'],
            'designation'           => $data['designation'],
            'phoneNumber'           => $data['phoneNumber'],
            'joiningDate'           => $data['joiningDate'],
            'checkInTime'           => Carbon::parse($timing->start_time)->timestamp,
            'checkOutTime'          => Carbon::parse($timing->end_time)->timestamp,
            'breakAllowed'          => Carbon::parse($timing->non_working_hour)->timestamp,
            'workingDays'           => $count,
            'cnic_no'               => $data['cnic']?$data['cnic']:null,
            'ntn_no'                => $data['ntn']?$data['ntn']:null,
            'bank_account_no'       => $data['account_no']?$data['account_no']:null,
            'blood_group'           => $data['blood_group']?$data['blood_group']:null,
            'allotted_leaves'       => 17,
            'shift_time'            => 1

        ]);
        $role = Role::findOrFail(1);
        $user->role()->attach($role);
        return view('Admin.index');

    }
    protected function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        return redirect()->route('admin.home');
    }



}
