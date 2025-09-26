<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['error' => 'Token required'], 401);
        }

        $validToken = config('app.admin_token', 'admin123');
        
        if ($token !== $validToken) {
            return response()->json(['error' => 'Invalid admin token'], 401);
        }
        
        return $next($request);
    }
}
