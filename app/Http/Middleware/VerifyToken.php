<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class VerifyToken
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');
        $user  = User::where(['token'=>$token])->first();

        if(!$token)
        {
            return response()->json([
                'error' => 'Please Provide Token'
            ], 401);
        }
        if ($user != null)
        {
            try
            {
                $credentials = JWT::decode($token, config('jwt.secret'), ['HS256']);
            }
            catch (ExpiredException $e) {

                return response()->json([
                    'error' => 'Your provided token is expired.'
                ], 400);

            } catch (Exception $e) {


                return response()->json([
                    'error' => 'Incorrecst Token.'
                ], 400);

            }
        }
        else
        {

            return response()->json([
                'error' => 'Incorrect Token.'
            ], 400);

        }
        $user = User::find($credentials->sub);
        $request->auth = $user;

        return $next($request);
    }
}