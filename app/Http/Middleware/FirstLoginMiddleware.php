<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FirstLoginMiddleware
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
        if (Auth::user()->isEmployee()) {
            if (Auth::user()->isfirstLogin()) {
                return $next($request);
                }
                 return redirect()->route('password');
        }
        return redirect()->back();
    }
}
