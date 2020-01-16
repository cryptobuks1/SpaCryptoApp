<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'data' => [
                    'error' => 'Page does not exist.'
                ],
            ], 404);
        }

        return $next($request);
    }
}
