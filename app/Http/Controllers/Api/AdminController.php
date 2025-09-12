<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\Conference;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        // В реальном приложении здесь должна быть проверка токена
        $validToken = config('app.admin_token', 'admin123');
        
        if ($request->token === $validToken) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ], 401);
    }

    public function getWorkers()
    {
        $workers = Worker::where('is_active', true)
            ->with(['conferences.bots'])
            ->get();
        
        return response()->json($workers->map(function ($worker) {
            return [
                'id' => $worker->id,
                'name' => $worker->name,
                'username' => $worker->username,
                'email' => $worker->email,
                'telegram_id' => $worker->telegram_id,
                'token' => $worker->tag,
                'tag' => $worker->tag,
                'created_at' => $worker->created_at->toISOString(),
                'conferences' => $worker->conferences->map(function ($conference) {
                    return [
                        'id' => $conference->id,
                        'title' => $conference->title,
                        'description' => $conference->description,
                        'invite_code' => $conference->invite_code,
                        'worker_tag' => $conference->worker_tag,
                        'worker_id' => $conference->worker_id,
                        'domain' => $conference->domain,
                        'is_active' => $conference->is_active,
                        'created_at' => $conference->created_at->toISOString(),
                        'updated_at' => $conference->updated_at->toISOString(),
                        'bots' => $conference->bots->map(function ($bot) {
                            return [
                                'id' => $bot->id,
                                'name' => $bot->name,
                                'avatar' => $bot->avatar,
                                'avatar_url' => $bot->avatar_url,
                                'mic' => $bot->mic,
                                'hand' => $bot->hand,
                                'created_at' => $bot->created_at->toISOString()
                            ];
                        })
                    ];
                })
            ];
        }));
    }

    public function getDownloadLinks()
    {
        $windows = Setting::getValue('download_links_windows', '');
        $mac = Setting::getValue('download_links_mac', '');

        return response()->json([
            'windows' => $windows,
            'mac' => $mac
        ]);
    }

    public function updateDownloadLinks(Request $request)
    {
        $request->validate([
            'windows' => 'nullable|string',
            'mac' => 'nullable|string'
        ]);

        Setting::setValue('download_links_windows', $request->windows ?? '');
        Setting::setValue('download_links_mac', $request->mac ?? '');

        return response()->json([
            'success' => true,
            'message' => 'Links updated'
        ]);
    }

    public function deleteWorker($id)
    {
        $worker = Worker::find($id);
        
        if (!$worker) {
            return response()->json(['error' => 'Worker not found'], 404);
        }

        $worker->delete();

        return response()->json([
            'success' => true,
            'message' => 'Worker deleted successfully'
        ]);
    }

    public function deleteConference($id)
    {
        $conference = Conference::find($id);
        
        if (!$conference) {
            return response()->json(['error' => 'Conference not found'], 404);
        }

        $conference->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conference deleted successfully'
        ]);
    }
}