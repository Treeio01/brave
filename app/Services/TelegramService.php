<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $botToken;
    protected $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage($message, $parseMode = 'HTML')
    {
        if (!$this->botToken || !$this->chatId) {
            Log::warning('Telegram bot not configured');
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true
            ]);

            if ($response->successful()) {
                Log::info('Telegram message sent successfully');
                return true;
            } else {
                Log::error('Failed to send Telegram message: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Telegram service error: ' . $e->getMessage());
            return false;
        }
    }

    public function notifyPageVisit($type, $reference, $ipAddress, $userAgent = null, $countryInfo = null)
    {
        $message = "Page visit\n";
        $message .= "Device: " . $this->detectDevice($userAgent) . "\n";
        
        if ($countryInfo && isset($countryInfo['country'])) {
            $message .= "Location: " . $ipAddress . " - " . $countryInfo['flag'] . " " . $countryInfo['country'] . "\n";
        } else {
            $message .= "Location: " . $ipAddress . " - " . $this->getCountryFlag($ipAddress) . "\n";
        }
        
        $message .= "Code: " . ($reference ?: 'NO_REF') . "\n";
        $message .= "User: " . ($this->extractUsername($userAgent) ?: 'N/A');

        return $this->sendMessage($message);
    }

    public function notifyConferenceJoin($conferenceTitle, $inviteCode, $ipAddress, $userAgent = null, $countryInfo = null)
    {
        $message = "Conference join\n";
        $message .= "Device: " . $this->detectDevice($userAgent) . "\n";
        
        if ($countryInfo && isset($countryInfo['country'])) {
            $message .= "Location: " . $ipAddress . " - " . $countryInfo['flag'] . " " . $countryInfo['country'] . "\n";
        } else {
            $message .= "Location: " . $ipAddress . " - " . $this->getCountryFlag($ipAddress) . "\n";
        }
        
        $message .= "Code: " . $inviteCode . "\n";
        $message .= "User: " . ($this->extractUsername($userAgent) ?: 'N/A');

        return $this->sendMessage($message);
    }

    public function notifyDownload($type, $reference, $platform, $ipAddress, $userAgent = null, $wallets = null, $countryInfo = null)
    {
        $message = "Download\n";
        $message .= "Device: " . ucfirst($platform) . "\n";
        
        if ($countryInfo && isset($countryInfo['country'])) {
            $message .= "Location: " . $ipAddress . " - " . $countryInfo['flag'] . " " . $countryInfo['country'] . "\n";
        } else {
            $message .= "Location: " . $ipAddress . " - " . $this->getCountryFlag($ipAddress) . "\n";
        }
        
        $message .= "Code: " . ($reference ?: 'NO_REF') . "\n";
        $message .= "User: " . ($this->extractUsername($userAgent) ?: 'N/A');

        return $this->sendMessage($message);
    }

    public function notifyWorkerAction($action, $workerName, $details = null)
    {
        $message = "<b>Worker Action</b>\n";
        $message .= "Action: " . $action . "\n";
        $message .= "Worker: " . $workerName . "\n";
        
        if ($details) {
            $message .= "Details: " . $details . "\n";
        }
        
        $message .= "Time: " . now()->format('d.m.Y H:i:s');

        return $this->sendMessage($message);
    }

    private function detectDevice($userAgent)
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        $userAgent = strtolower($userAgent);
        
        if (str_contains($userAgent, 'windows')) {
            return 'Windows';
        } elseif (str_contains($userAgent, 'mac')) {
            return 'MacOS';
        } elseif (str_contains($userAgent, 'linux')) {
            return 'Linux';
        } elseif (str_contains($userAgent, 'android')) {
            return 'Android';
        } elseif (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad')) {
            return 'iOS';
        }
        
        return 'Unknown';
    }

    private function getCountryFlag($ipAddress)
    {
        $countryFlags = [
            '105.113.28.2' => '🇳🇬 Nigeria',
            '127.0.0.1' => '🇷🇺 Russia',
            '192.168.' => '🇷🇺 Russia',
            '10.' => '🇷🇺 Russia',
            '172.' => '🇷🇺 Russia',
        ];

        foreach ($countryFlags as $ip => $flag) {
            if (str_starts_with($ipAddress, $ip)) {
                return $flag;
            }
        }

        return '🌍 Unknown';
    }

    private function extractUsername($userAgent)
    {
        if (!$userAgent) {
            return null;
        }

        if (preg_match('/user[:\s]+([a-zA-Z0-9_-]+)/i', $userAgent, $matches)) {
            return $matches[1];
        }

        if (preg_match('/username[:\s]+([a-zA-Z0-9_-]+)/i', $userAgent, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
