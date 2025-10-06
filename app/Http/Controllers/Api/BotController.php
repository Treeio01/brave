<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bot\CreateBotRequest;
use App\Http\Requests\Bot\SendBotMessageRequest;
use App\Services\BotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Worker;

class BotController extends Controller
{
    public function __construct(
        private BotService $botService
    ) {}

    public function index(Request $request, int $conferenceId): JsonResponse
    {

        $worker = $request->attributes->get('worker');

        if (!$worker) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $bots = $this->botService->getByConference($worker, $conferenceId);
        return Response::json(['bots' => $bots], 200);
    }

    public function store(CreateBotRequest $request, int $conferenceId): JsonResponse
    {

        $worker = $request->attributes->get('worker');

        if (!$worker) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $bot = $this->botService->create($worker, $conferenceId, $request->validated());
        return Response::json(['bot' => $bot], 201);
    }

    public function destroy(Request $request, int $conferenceId, int $botId): JsonResponse
    {

        $worker = $request->attributes->get('worker');

        if (!$worker) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $deleted = $this->botService->delete($worker, $conferenceId, $botId);

        if (!$deleted) {
            return Response::json(['error' => 'Bot not found'], 404);
        }

        return Response::json(['success' => true, 'message' => 'Bot deleted'], 200);
    }

    public function sendMessage(SendBotMessageRequest $request, int $conferenceId, int $botId): JsonResponse
    {

        $worker = $request->attributes->get('worker');

        if (!$worker) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $this->botService->sendMessage($worker, $conferenceId, $botId, $request->validated()['text']);
        return Response::json(['success' => true, 'message' => 'Message sent'], 200);
    }
}
