<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class SettingsController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {}

    public function getDownloadLinks(): JsonResponse
    {
        $links = $this->adminService->getDownloadLinks();
        return Response::json($links, 200);
    }
}