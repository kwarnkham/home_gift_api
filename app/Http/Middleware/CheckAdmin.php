<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        if (!$request->user()->is_admin) {
            return response()->json([
                'code' => '2',
                'msg' => 'Unauthorized'
            ]);
        }
        return $next($request);
    }
}
