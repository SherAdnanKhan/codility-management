<?php

namespace App\Http\Controllers\Api;
use Carbon\Carbon;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => Carbon::now()->endOfMonth()->timestamp
        ];


        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user)
        {

            return response()->json([
                'error' => 'Email does not exist/Login Already.'
            ], 400);
        }

        if (Hash::check($this->request->input('password'), $user->password))
        {
            $token=$this->jwt($user);
            $update=$user->update([
                'token' =>$token
            ]);

            if ($update){
                $users =User::select('name','capture_duration')->whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->get()->toArray();
            }
            return response()->json([
                'employee'=> collect($users),
                'token' => $token,
                'email' => $user->email,
            ], 200);

        }

        return response()->json([
            'error' => 'Invalid Email / Password.'
        ], 400);
    }
    public function logout(Request $request){

        $logout=$request->auth->update(['token'=>null]);
        if ($logout)
        {
            return response()->json([
                'success' => true,
            ], 200);
        }
        else
        {
            return response()->json([
                'success' => false,
            ], 400);
        }
    }

}