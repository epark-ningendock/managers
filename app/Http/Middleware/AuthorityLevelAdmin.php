<?php

namespace App\Http\Middleware;

use App\Enums\Authority;
use App\Staff;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorityLevelAdmin
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
        if (Auth::user()->authority->value !== Authority::ADMIN) {
            return redirect('/hospital');
        }
        return $next($request);
    }
}
