<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckHRMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()->checkHr() == true){
        return $next($request);
        }else{
            return redirect()->back()->with('status','You have no rights to access this, For more information contact Administrator');
        }

    }
}
