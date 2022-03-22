<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\View;


class checkAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    //protected $currentUser;

    public function handle(Request $request, Closure $next)
    {

        if (Session::has('id')) {

            $country = $request->segment(1) == 'hello';

            // add it to the request
            $request->merge(compact('country'));
            return $next($request);
        } else
            return redirect()->route('auth.login');
    }
}
