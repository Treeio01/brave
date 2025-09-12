<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Worker;
use Symfony\Component\HttpFoundation\Response;

class WorkerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['error' => 'Token required'], 401);
        }

        $worker = Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();

        if (!$worker) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Добавляем worker в request для использования в контроллерах
        $request->merge(['worker' => $worker]);

        return $next($request);
    }
}