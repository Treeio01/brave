<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default settings
        \App\Models\Setting::create([
            'key' => 'download_links_windows',
            'value' => 'https://example.com/app-windows.exe'
        ]);

        \App\Models\Setting::create([
            'key' => 'download_links_mac',
            'value' => 'https://example.com/app-mac.dmg'
        ]);

        // Create sample conference
        $conference = \App\Models\Conference::create([
            'invite_code' => 'sample123',
            'title' => 'Sample Conference',
            'description' => 'This is a sample conference',
            'worker_tag' => 'worker123',
            'is_active' => true
        ]);

        // Create sample bots for the conference
        \App\Models\Bot::create([
            'conference_id' => $conference->id,
            'name' => 'Bot 1',
            'avatar' => 'bot1.jpg',
            'mic' => true,
            'hand' => false,
            'avatar_url' => 'https://example.com/bot1.jpg'
        ]);

        \App\Models\Bot::create([
            'conference_id' => $conference->id,
            'name' => 'Bot 2',
            'avatar' => 'bot2.jpg',
            'mic' => false,
            'hand' => true,
            'avatar_url' => 'https://example.com/bot2.jpg'
        ]);

        // Create sample invite page
        \App\Models\InvitePage::create([
            'ref' => 'sample-ref',
            'title' => 'Sample Invite Page',
            'description' => 'This is a sample invite page',
            'conference_id' => $conference->id,
            'worker_tag' => 'worker456',
            'is_active' => true
        ]);

        // Create sample worker
        $worker = \App\Models\Worker::create([
            'name' => 'Sample Worker',
            'username' => 'sample_worker',
            'email' => 'worker@example.com',
            'password' => bcrypt('password'),
            'tag' => 'worker123',
            'telegram_id' => '123456789',
            'is_active' => true
        ]);

        // Update conference with worker_id
        $conference->update([
            'worker_id' => $worker->id
        ]);
    }
}
