<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\WorkerLoginRequest;
use App\Services\WorkerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function __construct(
        private WorkerService $workerService
    ) {}

    public function login(WorkerLoginRequest $request): JsonResponse
    {
        $worker = $this->workerService->authenticate($request->validated()['token']);

        if (!$worker) {
            return Response::json(['valid' => false], 401);
        }

        return Response::json([
            'valid' => true,
            'worker' => [
                'id' => $worker->id,
                'name' => $worker->name,
                'email' => $worker->email,
                'tag' => $worker->tag
            ]
        ], 200);
    }
}