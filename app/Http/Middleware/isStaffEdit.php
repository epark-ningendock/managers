<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Staff;
use Closure;
use Illuminate\Support\Facades\Auth;

class isStaffEdit
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
            if (Auth::user()->staff_auth->is_staff !== Permission::EDIT) {
                request()->session()->forget('hospital_id');
                return redirect('/hospital');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
