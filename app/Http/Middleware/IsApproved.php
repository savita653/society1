<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsApproved
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

        if(auth()->user()->hasRole('presenter') && !auth()->user()->approved()) {
            return redirect(route('user.account-pending'));
        }

        return $response;
    }
}
