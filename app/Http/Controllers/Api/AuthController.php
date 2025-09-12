<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $worker = Worker::where('tag', $request->token)
            ->where('is_active', true)
            ->first();

        if ($worker) {
            return response()->json([
                'valid' => true,
                'worker' => [
                    'id' => $worker->id,
                    'name' => $worker->name,
                    'email' => $worker->email,
                    'tag' => $worker->tag
                ]
            ]);
        }

        return response()->json([
            'valid' => false
        ], 401);
    }
}