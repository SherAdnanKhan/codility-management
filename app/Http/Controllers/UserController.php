<?php

namespace App\Http\Controllers;


use App\Role;
use App\User;
use Illuminate\Http\Request;
use AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showLoginForm()
    {
        return view('Admin.login');
    }
    public function showRegisterForm()
    {
        return view('Admin.register');
    }

    public function register(Request $request)
    {

//        $user ->role->create([''])
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
    public function changePassword(Request $request)
    {
//        dd($request->check);
        $user = User::whereEmail($request->check )->first();
            $user->password = bcrypt($request->password);
            $user->save();
        dd($user);
        return view('change_password')->withInput('password','email');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|alpha',
            'email' => 'required|email',
            'password' =>'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),


        ]);
        $role = Role::findOrFail(1);
        $user->role()->attach($role);
        return $user;
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
        //
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
        //
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
