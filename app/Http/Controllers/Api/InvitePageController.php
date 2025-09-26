<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitePage\CreateInvitePageRequest;
use App\Services\InvitePageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InvitePageController extends Controller
{
    public function __construct(
        private InvitePageService $invitePageService
    ) {}

    public function getWorkerTag(string $ref): JsonResponse
    {
        $tag = $this->invitePageService->getWorkerTag($ref);
        
        if (!$tag) {
            return Response::json(['error' => 'Invite page not found'], 404);
        }

        return Response::json(['tag' => $tag], 200);
    }

    public function getByRef(string $ref): JsonResponse
    {
        $page = $this->invitePageService->getByRef($ref);
        
        if (!$page) {
            return Response::json(['error' => 'Invite page not found'], 404);
        }

        return Response::json(['page' => $page], 200);
    }

    public function recordVisit(string $ref): JsonResponse
    {
        $this->invitePageService->recordVisit($ref);
        return Response::json(['success' => true, 'message' => 'Visit recorded'], 200);
    }

    public function recordDownload(string $ref, Request $request): JsonResponse
    {
        $data = $request->only(['platform', 'tag', 'wallets']);
        $this->invitePageService->recordDownload($ref, $data);
        return Response::json(['success' => true, 'message' => 'Download recorded'], 200);
    }

    public function index(): JsonResponse
    {
        $pages = $this->invitePageService->getAllActive();
        return Response::json(['pages' => $pages], 200);
    }

    public function store(CreateInvitePageRequest $request): JsonResponse
    {
        $token = $request->bearerToken();
        $page = $this->invitePageService->create($request->validated(), $token);

        return Response::json(['page' => $page], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->invitePageService->delete($id);
        
        if (!$deleted) {
            return Response::json(['error' => 'Page not found'], 404);
        }

        return Response::json(['success' => true, 'message' => 'Page deleted'], 200);
    }
}