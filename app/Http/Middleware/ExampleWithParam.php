<?php

namespace App\Http\Middleware;

use Closure;

class ExampleWithParam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $value)
    {
        error_log("this is an example! {$value}");
        return $next($request);
    }
}
