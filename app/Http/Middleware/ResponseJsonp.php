<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseJsonp
{
    /**
     * RESPONS jsonp format  
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // とりあえず型チェックはなし
        // if ($response instanceof JsonResponse) {
        return response()->json($response)->setCallback($request->query('callback'));
        // } 
//        return $response;
    }
}
