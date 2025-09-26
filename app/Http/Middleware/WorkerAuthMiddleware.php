<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkerAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['error' => 'Token required'], 401);
        }

        $worker = \App\Models\Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();

        if (!$worker) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $request->merge(['worker' => $worker]);
        
        return $next($request);
    }
}
