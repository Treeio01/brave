<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Conference;
use App\Models\Message;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index($conferenceId)
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
            })
        ]);
    }

    public function store(Request $request, $conferenceId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|file|image|max:2048'
        ]);

        $conference = Conference::findOrFail($conferenceId);

        $bot = new Bot();
        $bot->conference_id = $conferenceId;
        $bot->name = $request->name;
        $bot->mic = false;
        $bot->hand = false;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();
            $avatar->storeAs('public/avatars', $filename);
            $bot->avatar = $filename;
            $bot->avatar_url = asset('storage/avatars/' . $filename);
        }

        $bot->save();

        return response()->json([
            'bot' => [
                'id' => $bot->id,
                'name' => $bot->name,
                'avatar' => $bot->avatar,
                'mic' => $bot->mic,
                'hand' => $bot->hand,
                'avatar_url' => $bot->avatar_url
            ]
        ]);
    }

    public function destroy($conferenceId, $botId)
    {
        $bot = Bot::where('conference_id', $conferenceId)
            ->where('id', $botId)
            ->firstOrFail();

        $bot->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bot deleted'
        ]);
    }

    public function sendMessage(Request $request, $conferenceId, $botId)
    {
        $request->validate([
            'text' => 'required|string'
        ]);

        $bot = Bot::where('conference_id', $conferenceId)
            ->where('id', $botId)
            ->firstOrFail();

        $message = Message::create([
            'conference_id' => $conferenceId,
            'sender' => 'bot_' . $bot->name,
            'text' => $request->text
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent'
        ]);
    }
}