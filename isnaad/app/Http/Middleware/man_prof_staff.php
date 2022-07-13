<?php

namespace App\Http\Middleware;

use Closure;

class man_prof_staff
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

        if($request->user()!=null && ($request->user()->type=='m' || $request->user()->type=='s' || $request->user()->type=='p' )){
            return $next($request);

        }elseif ($request->user()->type=='a'){
            return redirect('ClientDashboard');
        }
        return redirect('logout');
    }
}
