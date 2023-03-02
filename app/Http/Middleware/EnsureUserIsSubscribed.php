<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin')) {
            return $response;
        }

        if(!auth()->user()->subscribed('default')) {
            return redirect(route('subscription-required'));
        }


        return $response;
    }
}
