<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Worker;
use Illuminate\Support\Facades\Response;

class WorkerController extends Controller
{
    public function __construct(
        private WorkerService $workerService
    ) {}

    public function me(Request $request): JsonResponse
    {

        $worker = $request->attributes->get('worker');

        if (!$worker) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $data = $this->workerService->getWorkerWithConferences($worker);

        return Response::json($data, 200);
    }

    public function index(): JsonResponse
    {
        $workers = $this->workerService->getAllWorkers();
        return Response::json($workers, 200);
    }
}
