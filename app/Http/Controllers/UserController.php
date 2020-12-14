<?php

namespace App\Http\Controllers;


use App\Photo;
use App\Role;
use App\User;
use Carbon\Carbon;
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showRegisterForm()
    {
        return view('Admin.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'name' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return view('Admin.index');
    }

    public function index()
    {
        $users = User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->orderByDesc('id')->paginate(10);

        return view('Employee.index', compact('users'));
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
        }if (Auth::user()->isAdmin() == true) {
            return redirect()->route('admin.home');

        }

    }

    public function changePassword()
    {
        $email = Auth::user()->email;
        $user = User::whereEmail($email)->where('firstLogin', 1)->first();
        return view('change_password', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:6',
            'confirm_password'      => 'required|same:password',
            'name'                  => 'required',
            'designation'           => 'required',

        ]);
        $user = User::create([
            'name'                  => $request->name,
            'email'                 => $request->email,
            'password'              => bcrypt($request->password),
            'designation'           => $request->designation,


        ]);
        $role = Role::findOrFail(2);
        $user->role()->attach($role);
        return view('Admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
       $users=User::whereName($request->name)->paginate(10);
        return view('Employee.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->isAdmin()) {
            $user = User::findOrFail($id);
        } elseif (Auth::user()->isEmployee()) {
            $user = User::findOrFail(Auth::user()->id);
        }
        return view('Admin.employee_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image'                 => 'mimes:jpeg,jpg',
//            'cnic'                  => 'integer',
            'workingDays'           => 'integer|required|max:7|min:5',
            'non_working_hour'      => 'required'
        ]);
        $user = User::findOrFail($id);

        if (Auth::user()->isAdmin()){
            $check_in_time = Carbon::parse($request->check_in_time )->timestamp;
            $check_out_time = Carbon::parse( $request->check_out_time )->timestamp;

            $update= User::whereId($id)->update([
                'abended'               => $request->abended?true:false,
                'email'                 => $request->email,
                'name'                  => $request->name,
                'checkInTime'           => $check_in_time,
                'checkOutTime'          => $check_out_time,
                'opening_balance'       => $request->opening_balance?$request->opening_balance:false,
                'cnic_no'               => $request->cnic?$request->cnic:null,
                'ntn_no'                => $request->ntn?$request->ntn:null,
                'bank_account_no'       => $request->account_no?$request->account_no:null,
                'blood_group'           => $request->blood_group?$request->blood_group:null,
                'is_hr'                 => $request->is_hr?true:false,
                'compensatory_leaves'   => $request->compensatory_leaves?$request->compensatory_leaves:$user->compensatory_leaves,
                'workingDays'           => $request->workingDays?$request->workingDays:5,
                'breakAllowed'          => Carbon::parse($request->non_working_hour)->timestamp
                ]);
        } else{
            $update= User::whereId($id)->update([
                'name'              => $request->name,
                'designation'       => $request->designation,
                'address'           => $request->address,
                'qualification'     => $request->qualification,
                'phoneNumber'       => $request->contact,

            ]);
        }
        if ($file = $request->file('image')) {
            $name = $request->name . time() . $file->getClientOriginalName();
            $file->move('images/user', $name);
            $photo = Photo::whereUserId($id)->get()->first();
            if (!(empty($photo))) {
                unlink(public_path('/images/user/' . $user->photos->path));
                Photo::whereUserId($id)->update(['path' => $name]);
                } else {
                $user->photos()->create(['path' => $name]);
            }
        }
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('employee.home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id /
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        $photo = $user->photos;
        $user->delete();
        if (!(empty($photo))) {
            $photo->delete();
            unlink(public_path('/images/user/' . $photo->path));
        }
        return redirect()->route('profile.index');
        }

    public function screenCapture($id){
        if (Auth::user()->isAdmin()) {
            $data = User::whereId($id)->first();
            return \response()->json($data);
        }
    }

     public function screenCapturePage(){

        if (Auth::user()->isAdmin()) {
            $users = User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->orderByDesc('id')->paginate(10);
            return view('CaptureTime.index', compact('users'));
        }
    }
    public function screenCaptureUpdate(Request $request ,$id){

        if (Auth::user()->isAdmin()) {

            $time= $request->time_capture_duration?$request->time_capture_duration:null;
            $imperative_minutes=$request->imperative_minutes?$request->imperative_minutes:null;
            if ($time > '10'){
                return redirect()->back()->with('status','Select time below 10 Minutes');
            }

            if($time != null){
                $get_time=Carbon::createFromTime($time);

                $users = User::whereId($id)->update([
                    'capture_duration'      => $time,
                    ]);
                return redirect()->route('screen.capture.page');
            } if ($imperative_minutes != null){

                $users = User::whereId($id)->update([
                    'imperative_minutes'    => $imperative_minutes
                ]);
                return redirect()->route('screen.capture.page');

            }

            if($time == null && $imperative_minutes == null){
                return redirect()->back()->with('status','Incorrect Duration/Imperative Minutes');
            }
            }
    }
    public function indexAdmin()
    {
        $admins = User::whereHas('role',function ($q){$q->whereIn('name',['Administrator']);})->orderByDesc('id')->paginate(10);

        return view('Admin.admin_list', compact('admins'));
    }
}

