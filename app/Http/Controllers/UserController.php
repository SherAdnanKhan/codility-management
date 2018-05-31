<?php

namespace App\Http\Controllers;


use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public  function __construct()
    {
        $this->middleware('auth');
    }

    public function showRegisterForm()
    {
        return view('Admin.register');
    }

    public function register(Request $request)
    {

        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password',
            'name'            =>'required'

        ]);

        $user =User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);
        return view('Admin.index');
    }
    public function login(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6'


        ]);
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password],$request->remember)){

            return redirect()->intended('/dashboard');
        }
        return redirect()->back()->withInput()->withFlashMessage('Wrong username/password combination.');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //;
    }
    public function newPassword(Request $request)
    {
        $this->validate($request, [
            'new_password' => 'required|min:6',
        ]);
        $email = Auth::user()->email;
        $user = User::whereEmail($email)->update(['password' => bcrypt($request->new_password), 'firstLogin' => 0]);

        if (Auth::user()->isEmployee() == true) {
            return redirect()->route('employee.home');
        }

        if(Auth::user()->isAdmin() == true)
        {

            return redirect()->route('admin.home');

        }

    }
    public function changePassword()
    {

        $email = Auth::user()->email;
        $user = User::whereEmail($email)->first();
        return view('change_password',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password',
            'name'            =>'required'

        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),


        ]);
        $role = Role::findOrFail(1);
        $user->role()->attach($role);
        return view('Admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//dd(Auth::user()->id);
        $get_id= Auth::user()->isAdmin() ? $id : Auth::user()->id;
//        dd($get_id);

        $user = User::findOrFail($get_id);
        if($user->isEmployee()) {
//dd($user);
            return view('Admin.employee_edit', compact('user'));

        }
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->isAdmin) {

            $user->email =$request->email;
            $user->name = $request->name;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
