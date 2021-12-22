<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAcrossRequest
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
        if (!Auth::check()) 
        {
            $next_url_arr = explode('/', $request->fullUrl());
            if(in_array('admin', $next_url_arr))
                return redirect('/admin/login');
            else 
                return redirect('/');
        }

        // if (Auth::check())
        // {
        //     $pre_url_arr = explode('/', $request->headers->get('referer'));
        //     $next_url_arr = explode('/', $request->fullUrl());
        //     if(!in_array('admin', $pre_url_arr))
        //     {
        //         if(in_array('admin', $next_url_arr) && Auth::user()->role == 'admin')
        //             return $next($request);
        //         else
        //             return redirect('/logout');
        //     }
        // }
        return $next($request);
    }
}
