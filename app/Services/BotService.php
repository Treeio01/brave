<?php

namespace App\Services;

use App\Models\Bot;
use App\Models\Conference;
use App\Models\Message;

class BotService
{
    public function getByConference(int $conferenceId): array
    {
        $conference = Conference::findOrFail($conferenceId);
        $bots = $conference->bots;

        return $bots->map(function ($bot) {
            return [
                'id' => $bot->id,
                'name' => $bot->name,
                'avatar' => $bot->avatar,
                'mic' => $bot->mic,
                'hand' => $bot->hand,
                'avatar_url' => $bot->avatar_url
            ];
        })->toArray();
    }

    public function create(int $conferenceId, array $data): array
    {
        $conference = Conference::findOrFail($conferenceId);

        $bot = new Bot();
        $bot->conference_id = $conferenceId;
        $bot->name = $data['name'];
        $bot->mic = false;
        $bot->hand = false;

        if (isset($data['avatar']) && $data['avatar']) {
            $avatar = $data['avatar'];
            $filename = time() . '_' . $avatar->getClientOriginalName();
            $avatar->storeAs('public/avatars', $filename);
            $bot->avatar = $filename;
            $bot->avatar_url = asset('storage/avatars/' . $filename);
        }

        $bot->save();

        return [
            'id' => $bot->id,
            'name' => $bot->name,
            'avatar' => $bot->avatar,
            'mic' => $bot->mic,
            'hand' => $bot->hand,
            'avatar_url' => $bot->avatar_url
        ];
    }

    public function delete(int $conferenceId, int $botId): bool
    {
        $bot = Bot::where('conference_id', $conferenceId)
            ->where('id', $botId)
            ->first();

        if (!$bot) {
            return false;
        }

        return $bot->delete();
    }

    public function sendMessage(int $conferenceId, int $botId, string $text): bool
    {
        $bot = Bot::where('conference_id', $conferenceId)
            ->where('id', $botId)
            ->firstOrFail();

        Message::create([
            'conference_id' => $conferenceId,
            'sender' => 'bot_' . $bot->name,
            'text' => $text
        ]);

        return true;
    }
}
