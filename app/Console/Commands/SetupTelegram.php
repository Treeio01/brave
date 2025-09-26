<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetupTelegram extends Command
{
    protected $signature = 'telegram:setup {bot_token} {chat_id}';
    protected $description = 'Setup Telegram bot configuration';

    public function handle()
    {
        $botToken = $this->argument('bot_token');
        $chatId = $this->argument('chat_id');

        try {
            $response = Http::get("https://api.telegram.org/bot{$botToken}/getMe");
            
            if ($response->successful()) {
                $botInfo = $response->json();
                $this->info("Bot found: @{$botInfo['result']['username']}");
                
                $testResponse = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => '<b>Telegram Bot configured!</b>' . "\n" .
                             'Bot is ready to work' . "\n" .
                             now()->format('d.m.Y H:i:s'),
                    'parse_mode' => 'HTML'
                ]);
                
                if ($testResponse->successful()) {
                    $this->info("Add these to your .env file:");
                    $this->line("TELEGRAM_BOT_TOKEN={$botToken}");
                    $this->line("TELEGRAM_CHAT_ID={$chatId}");
                } else {
                    $this->error("Failed to send test message. Check chat_id.");
                }
            } else {
                $this->error("Invalid bot token");
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
