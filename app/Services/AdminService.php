<?php

namespace App\Services;

use App\Models\Worker;
use App\Models\Conference;
use App\Models\Setting;

class AdminService
{
    public function authenticate(string $token): bool
    {
        $validToken = config('app.admin_token', 'admin123');
        return $token === $validToken;
    }

    public function getWorkers(): array
    {
        return Worker::where('is_active', true)
            ->with(['conferences.bots'])
            ->get()
            ->map(function ($worker) {
                return [
                    'id' => $worker->id,
                    'name' => $worker->name,
                    'username' => $worker->username,
                    'email' => $worker->email,
                    'telegram_id' => $worker->telegram_id,
                    'token' => $worker->tag,
                    'tag' => $worker->tag,
                    'created_at' => $worker->created_at->toISOString(),
                    'conferences' => $worker->conferences->map(function ($conference) {
                        return [
                            'id' => $conference->id,
                            'title' => $conference->title,
                            'description' => $conference->description,
                            'invite_code' => $conference->invite_code,
                            'worker_tag' => $conference->worker_tag,
                            'worker_id' => $conference->worker_id,
                            'domain' => $conference->domain,
                            'is_active' => $conference->is_active,
                            'created_at' => $conference->created_at->toISOString(),
                            'updated_at' => $conference->updated_at->toISOString(),
                            'bots' => $conference->bots->map(function ($bot) {
                                return [
                                    'id' => $bot->id,
                                    'name' => $bot->name,
                                    'avatar' => $bot->avatar,
                                    'avatar_url' => $bot->avatar_url,
                                    'mic' => $bot->mic,
                                    'hand' => $bot->hand,
                                    'created_at' => $bot->created_at->toISOString()
                                ];
                            })
                        ];
                    })
                ];
            })
            ->toArray();
    }

    public function getDownloadLinks(): array
    {
        return [
            'windows' => Setting::getValue('download_links_windows', ''),
            'mac' => Setting::getValue('download_links_mac', '')
        ];
    }

    public function updateDownloadLinks(array $data): bool
    {
        Setting::setValue('download_links_windows', $data['windows'] ?? '');
        Setting::setValue('download_links_mac', $data['mac'] ?? '');
        
        return true;
    }

    public function deleteWorker(int $id): bool
    {
        $worker = Worker::find($id);
        
        if (!$worker) {
            return false;
        }

        return $worker->delete();
    }

    public function deleteConference(int $id): bool
    {
        $conference = Conference::find($id);
        
        if (!$conference) {
            return false;
        }

        return $conference->delete();
    }
}
