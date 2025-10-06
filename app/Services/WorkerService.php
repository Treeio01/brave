<?php

namespace App\Services;

use App\Models\Worker;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WorkerService
{
    public function __construct(
        private TelegramService $telegramService
    ) {}

    public function authenticate(string $token): ?Worker
    {
        return Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();
    }

    public function create(array $data): Worker
    {
        $tag = 'worker_' . Str::random(8);

        $worker = Worker::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tag' => $tag,
            'telegram_id' => $data['telegram_id'] ?? null,
            'is_active' => true
        ]);

        $this->telegramService->notifyWorkerAction(
            'Created new worker',
            $worker->name,
            "Email: {$worker->email}, Tag: {$worker->tag}"
        );

        return $worker;
    }

    public function getWorkerWithConferences(Worker $worker): array
    {
        $conferences = $worker->conferences()
            ->where('is_active', true)
            ->with('bots')
            ->get();

        return [
            'worker' => [
                'id' => $worker->id,
                'telegram_id' => $worker->telegram_id,
                'username' => $worker->username ?? $worker->name,
                'token' => $worker->tag,
                'tag' => $worker->tag
            ],
            'conferences' => $conferences->map(function ($conference) {
                return [
                    'id' => $conference->id,
                    'worker_id' => $conference->worker_id,
                    'title' => $conference->title,
                    'link' => "https://app/room/{$conference->invite_code}",
                    'created_at' => $conference->created_at->toISOString(),
                    'invite_code' => $conference->invite_code,
                    'invite_link' => "/{$conference->invite_code}",
                    'domain' => $conference->domain,
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
        ];
    }

    public function getAllWorkers(): array
    {
        return Worker::where('is_active', true)
            ->get()
            ->map(function ($worker) {
                return [
                    'id' => $worker->id,
                    'name' => $worker->name,
                    'email' => $worker->email,
                    'tag' => $worker->tag,
                    'created_at' => $worker->created_at->toISOString()
                ];
            })
            ->toArray();
    }

    public function delete(int $id): bool
    {
        $worker = Worker::find($id);
        
        if (!$worker) {
            return false;
        }

        $this->telegramService->notifyWorkerAction(
            'Deleted worker',
            $worker->name,
            "Email: {$worker->email}"
        );

        return $worker->delete();
    }
}
