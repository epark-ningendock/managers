<?php

namespace App\Http\Middleware;

use Closure;

class RequestMiddleware
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
        if (in_array(app()->environment(), ["staging", "production", "development"])) {
            $this->writeLog($request);
        }
        return $next($request);
    }

    private function writeLog()
    {
        \Log::info(url()->current());
        \DB::listen(function ($query) {
            \Log::debug("Query Time:{$query->time}s] $query->sql");
        });
    }
}