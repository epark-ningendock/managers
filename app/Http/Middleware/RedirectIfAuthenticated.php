<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if (Auth::guard($guard)->check()) {
        //     // ログインユーザーがスタッフの場合、スタッフ一覧に遷移する
        //     dd(Auth::user());
        //     // ログインユーザーが医療機関スタッフの場合、医療機関スタッフ一覧に遷移する
        //     return redirect('/');
        // }

        return $next($request);
    }
}
