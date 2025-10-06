<?php

namespace App\Services;

use App\Models\Bot;
use App\Models\Conference;
use App\Models\Message;
use App\Models\Worker;

class BotService
{
    public function getByConference(Worker $worker, int $conferenceId): array
    {
        $conference = $this->getOwnedConference($worker, $conferenceId);
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

    public function create(Worker $worker, int $conferenceId, array $data): array
    {
        $conference = $this->getOwnedConference($worker, $conferenceId);

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

    public function delete(Worker $worker, int $conferenceId, int $botId): bool
    {
        $bot = Bot::where('conference_id', $conferenceId)
            ->where('id', $botId)
            ->whereHas('conference', function ($query) use ($worker) {
                $query->where('worker_id', $worker->id);
            })
            ->first();

        if (!$bot) {
            return false;
        }

        return $bot->delete();
    }

    public function sendMessage(Worker $worker, int $conferenceId, int $botId, string $text): bool
    {
        $bot = Bot::where('conference_id', $conferenceId)
            ->where('id', $botId)
            ->whereHas('conference', function ($query) use ($worker) {
                $query->where('worker_id', $worker->id);
            })
            ->firstOrFail();

        Message::create([
            'conference_id' => $conferenceId,
            'sender' => 'bot_' . $bot->name,
            'text' => $text
        ]);

        return true;
    }

    private function getOwnedConference(Worker $worker, int $conferenceId): Conference
    {
        return Conference::where('id', $conferenceId)
            ->where('worker_id', $worker->id)
            ->firstOrFail();
    }
}
