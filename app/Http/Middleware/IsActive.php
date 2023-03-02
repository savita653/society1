<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class IsActive
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

        if( !auth()->user()->is_active ) {
            Auth::logout();
            if($request->ajax()) {
                echo '<p class="text-danger">Your account in inactive, <a href="' . config('setting.contact_url') . '">Click Here</a> to contact for more information.</p>'; die;
            } else {
                return redirect(route('login'))->with('account_inactive', true);
            }
        }

        return $response;
    }
}
