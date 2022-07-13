<?php

namespace App\Http\Middleware;

use Closure;

class bulk_ship
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next )
    {


        if($request->user()!=null && ($request->user()->type=='m' || $request->user()->type=='p' || $request->user()->hasRole('bulk_ship'))){
            return $next($request);
        }else{

            return redirect('not-found');
        }
    }
}
