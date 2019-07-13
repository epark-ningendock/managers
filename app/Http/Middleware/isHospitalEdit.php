<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Staff;
use Closure;

class isHospitalEdit
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
        
        if ($staff->staff_auth->is_hospital === Permission::None) {
            return redirect('/hospital');
        }
        return $next($request);
    }
}
