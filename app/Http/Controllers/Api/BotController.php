<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bot\CreateBotRequest;
use App\Http\Requests\Bot\SendBotMessageRequest;
use App\Services\BotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class BotController extends Controller
{
    public function __construct(
        private BotService $botService
    ) {}

    public function index(int $conferenceId): JsonResponse
    {
        $bots = $this->botService->getByConference($conferenceId);
        return Response::json(['bots' => $bots], 200);
    }

    public function store(CreateBotRequest $request, int $conferenceId): JsonResponse
    {
        $bot = $this->botService->create($conferenceId, $request->validated());
        return Response::json(['bot' => $bot], 201);
    }

    public function destroy(int $conferenceId, int $botId): JsonResponse
    {
        $deleted = $this->botService->delete($conferenceId, $botId);
        
        if (!$deleted) {
            return Response::json(['error' => 'Bot not found'], 404);
        }

        return Response::json(['success' => true, 'message' => 'Bot deleted'], 200);
    }

    public function sendMessage(SendBotMessageRequest $request, int $conferenceId, int $botId): JsonResponse
    {
        $this->botService->sendMessage($conferenceId, $botId, $request->validated()['text']);
        return Response::json(['success' => true, 'message' => 'Message sent'], 200);
    }
}