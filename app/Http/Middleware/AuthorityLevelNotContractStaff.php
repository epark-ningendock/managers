<?php

namespace App\Http\Middleware;

use App\Enums\Authority;
use App\Staff;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorityLevelNotContractStaff
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
            
            if ($staff->authority->value === Authority::ContractStaff) {
                request()->session()->forget('hospital_id');
                // TODO: 契約管理が出来次第実装する
                return redirect('/');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
