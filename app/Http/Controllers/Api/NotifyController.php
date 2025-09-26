<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notify\DownloadNotificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class NotifyController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function download(DownloadNotificationRequest $request): JsonResponse
    {
        $this->notificationService->recordDownload($request->validated());
        return Response::json(['success' => true, 'message' => 'Notification sent'], 200);
    }
}