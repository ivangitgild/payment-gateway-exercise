<?php

namespace App\Http\Middleware;

use Closure;

class VerifyCode
{

    public function handle($request, Closure $next)
    {
        if (!$request->header('service-header') || $request->header('service-header') !== config('services.service_header'))
        {
            return response()->json(['error' => 'Unauthorized Access'], 401);
        }

        return $next($request);
    }
}
