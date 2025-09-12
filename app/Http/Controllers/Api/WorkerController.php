<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\Conference;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function me(Request $request)
    {
        $token = $request->bearerToken();
        $worker = Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();

        if (!$worker) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Получаем конференции воркера с ботами
        $conferences = Conference::where('worker_tag', $worker->tag)
            ->where('is_active', true)
            ->with('bots')
            ->get();

        return response()->json([
            'worker' => [
                'id' => $worker->id,
                'telegram_id' => $worker->telegram_id ?? null,
                'username' => $worker->username ?? $worker->name,
                'token' => $worker->tag,
                'tag' => $worker->tag
            ],
            'conferences' => $conferences->map(function ($conference) {
                return [
                    'id' => $conference->id,
                    'worker_id' => $conference->worker_id ?? null,
                    'title' => $conference->title,
                    'link' => "https://app/room/{$conference->invite_code}",
                    'created_at' => $conference->created_at->toISOString(),
                    'invite_code' => $conference->invite_code,
                    'invite_link' => "/{$conference->invite_code}",
                    'domain' => $conference->domain ?? null,
                    'visits' => $conference->visits()->count(),
                    'downloads' => $conference->downloads()->count(),
                    'last_visit' => $conference->visits()->latest()->first()?->created_at?->toISOString(),
                    'bots' => $conference->bots->map(function ($bot) {
                        return [
                            'id' => $bot->id,
                            'name' => $bot->name,
                            'avatar' => $bot->avatar,
                            'avatar_url' => $bot->avatar_url
                        ];
                    })
                ];
            })
        ]);
    }

    public function index()
    {
        $workers = Worker::where('is_active', true)->get();
        
        return response()->json($workers->map(function ($worker) {
            return [
                'id' => $worker->id,
                'name' => $worker->name,
                'email' => $worker->email,
                'tag' => $worker->tag,
                'created_at' => $worker->created_at->toISOString()
            ];
        }));
    }
}