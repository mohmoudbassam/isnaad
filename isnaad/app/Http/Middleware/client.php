<?php

namespace App\Http\Middleware;

use Closure;

class client
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

        if($request->user()!=null && $request->user()->type=='a'){
            return $next($request);

        }else{
            return redirect('/notfound');
        }

    }
}
