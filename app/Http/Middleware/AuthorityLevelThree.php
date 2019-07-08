<?php

namespace App\Http\Middleware;

use App\Enums\Authority;
use App\Staff;
use Closure;

class AuthorityLevelThree
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
        $staff = Staff::findOrFail($request->staff_id);

        if ($staff->authority->value !== Authority::ExternalStaff) {
            return redirect('/hospital');
        }
        return $next($request);
    }
}
