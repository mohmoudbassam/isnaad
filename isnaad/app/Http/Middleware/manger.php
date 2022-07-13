<?php

namespace App\Http\Middleware;

use Closure;

class manger
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
        if($request->user()==null || $request->user()->type!='m'){
            return redirect('Processing');
        }
        return $next($request);
    }
}
