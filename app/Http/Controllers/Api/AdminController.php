<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\CreateWorkerRequest;
use App\Http\Requests\Admin\UpdateDownloadLinksRequest;
use App\Services\AdminService;
use App\Services\WorkerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService,
        private WorkerService $workerService
    ) {}

    public function login(AdminLoginRequest $request): JsonResponse
    {
        $authenticated = $this->adminService->authenticate($request->validated()['token']);
        
        if (!$authenticated) {
            return Response::json(['success' => false], 401);
        }

        return Response::json(['success' => true], 200);
    }

    public function getWorkers(): JsonResponse
    {
        $workers = $this->adminService->getWorkers();
        return Response::json($workers, 200);
    }

    public function getDownloadLinks(): JsonResponse
    {
        $links = $this->adminService->getDownloadLinks();
        return Response::json($links, 200);
    }

    public function updateDownloadLinks(UpdateDownloadLinksRequest $request): JsonResponse
    {
        $this->adminService->updateDownloadLinks($request->validated());
        return Response::json(['success' => true, 'message' => 'Links updated'], 200);
    }

    public function createWorker(CreateWorkerRequest $request): JsonResponse
    {
        $worker = $this->workerService->create($request->validated());
        
        return Response::json([
            'worker' => [
                'id' => $worker->id,
                'name' => $worker->name,
                'email' => $worker->email,
                'tag' => $worker->tag,
                'created_at' => $worker->created_at->toISOString()
            ]
        ], 201);
    }

    public function deleteWorker(int $id): JsonResponse
    {
        $deleted = $this->adminService->deleteWorker($id);
        
        if (!$deleted) {
            return Response::json(['error' => 'Worker not found'], 404);
        }

        return Response::json(['success' => true, 'message' => 'Worker deleted successfully'], 200);
    }

    public function deleteConference(int $id): JsonResponse
    {
        $deleted = $this->adminService->deleteConference($id);
        
        if (!$deleted) {
            return Response::json(['error' => 'Conference not found'], 404);
        }

        return Response::json(['success' => true, 'message' => 'Conference deleted successfully'], 200);
    }
}