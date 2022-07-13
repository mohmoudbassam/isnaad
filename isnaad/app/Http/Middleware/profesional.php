<?php

namespace App\Http\Middleware;

use Closure;

class profesional
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
        if( $request->user()->type!='p'){
            return redirect('Processing');
        }
        return $next($request);
    }
}
