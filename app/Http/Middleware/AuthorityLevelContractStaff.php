<?php

namespace App\Http\Middleware;

use App\Enums\Authority;
use App\Staff;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorityLevelContractStaff
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
        if (Auth::user()->getTable() == "staffs") {
            $staff = Staff::findOrFail(request()->session()->get('staffs'));
            
            if ($staff->authority->value !== Authority::CONTRACT_STAFF) {
                request()->session()->forget('hospital_id');
                return redirect('/hospital');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
