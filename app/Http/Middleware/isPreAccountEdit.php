<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Staff;
use Closure;
use Illuminate\Support\Facades\Auth;

class isPreAccountEdit
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
            
            if ($staff->staff_auth->is_pre_account !== Permission::EDIT) {
                request()->session()->forget('hospital_id');
                return redirect('/hospital');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
