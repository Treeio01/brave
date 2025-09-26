<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Conference\CreateConferenceRequest;
use App\Http\Requests\Conference\JoinConferenceRequest;
use App\Http\Requests\Conference\SendMessageRequest;
use App\Services\ConferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ConferenceController extends Controller
{
    public function __construct(
        private ConferenceService $conferenceService
    ) {}

    public function getWorkerTag(int $conferenceId): JsonResponse
    {
        $tag = $this->conferenceService->getWorkerTag($conferenceId);
        return Response::json(['tag' => $tag], 200);
    }

    public function recordVisit(int $conferenceId): JsonResponse
    {
        $this->conferenceService->recordVisit($conferenceId);
        return Response::json(['success' => true, 'message' => 'Visit recorded'], 200);
    }

    public function getMessages(int $conferenceId): JsonResponse
    {
        $messages = $this->conferenceService->getMessages($conferenceId);
        return Response::json(['data' => $messages], 200);
    }

    public function recordDownload(int $conferenceId, Request $request): JsonResponse
    {
        $data = $request->only(['platform', 'tag', 'wallets']);
        $this->conferenceService->recordDownload($conferenceId, $data);
        return Response::json(['success' => true, 'message' => 'Download recorded'], 200);
    }

    public function join(string $inviteCode): JsonResponse
    {
        $data = $this->conferenceService->join($inviteCode);
        
        if (!$data) {
            return Response::json(['error' => 'Conference not found'], 404);
        }

        return Response::json($data, 200);
    }

    public function joinWithName(JoinConferenceRequest $request, string $inviteCode): JsonResponse
    {
        $data = $this->conferenceService->join($inviteCode, $request->validated()['name']);
        
        if (!$data) {
            return Response::json(['error' => 'Conference not found'], 404);
        }

        return Response::json(['conferenceId' => $data['conference']['id']], 200);
    }

    public function index(): JsonResponse
    {
        $conferences = $this->conferenceService->getAllActive();
        return Response::json(['conferences' => $conferences], 200);
    }

    public function store(CreateConferenceRequest $request): JsonResponse
    {
        $token = $request->bearerToken();
        $conference = $this->conferenceService->create($request->validated(), $token);

        return Response::json([
            'conference' => [
                'id' => $conference->id,
                'title' => $conference->title,
                'invite_code' => $conference->invite_code,
                'worker_tag' => $conference->worker_tag,
                'created_at' => $conference->created_at->toISOString()
            ]
        ], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->conferenceService->delete($id);
        
        if (!$deleted) {
            return Response::json(['error' => 'Conference not found'], 404);
        }

        return Response::json(['success' => true, 'message' => 'Conference deleted'], 200);
    }

    public function getMembers(int $conferenceId): JsonResponse
    {
        $members = $this->conferenceService->getMembers($conferenceId);
        return Response::json($members, 200);
    }

    public function sendMessage(SendMessageRequest $request, int $conferenceId): JsonResponse
    {
        $this->conferenceService->sendMessage($conferenceId, $request->validated());
        return Response::json(['success' => true, 'message' => 'Message sent'], 200);
    }
}
