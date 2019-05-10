<?php

namespace App\Http\Controllers;

use App\Mail\MailEmployeeNoNtn;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $users=User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->where(['abended'=>false,'ntn_no'=>null])->pluck('name','id')->toArray();
        return view('Admin.index',compact('users'));
    }
    public function sendMailNoNTN($id){
        $employee_detail=User::select('name','email')->whereId($id)->first();
        if ($employee_detail != null){
            Mail::send(new MailEmployeeNoNtn($employee_detail));
            return redirect()->route('admin.home')->with('status',"Notification Send to $employee_detail->name");

        }else{
            return redirect()->route('admin.home')->with('status',"Notification failed send  to $employee_detail->name");

        }
    }
    public function employeeHome()
    {
        return view('home');
    }
}