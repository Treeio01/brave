<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConferenceController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\InvitePageController;
use App\Http\Controllers\Api\NotifyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\BotController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('workers')->group(function () {
    Route::get('/', [WorkerController::class, 'index']);
    Route::get('/me', [WorkerController::class, 'me'])->middleware('worker.auth');
});

Route::prefix('conferences')->group(function () {
    Route::get('/', [ConferenceController::class, 'index'])->middleware('worker.auth');
    Route::post('/', [ConferenceController::class, 'store'])->middleware('worker.auth');
    Route::delete('/{id}', [ConferenceController::class, 'destroy'])->middleware('worker.auth');
    Route::get('/{conferenceId}/worker-tag', [ConferenceController::class, 'getWorkerTag']);
    Route::post('/{conferenceId}/visit', [ConferenceController::class, 'recordVisit']);
    Route::get('/{conferenceId}/messages', [ConferenceController::class, 'getMessages']);
    Route::post('/{conferenceId}/messages', [ConferenceController::class, 'sendMessage']);
    Route::post('/{conferenceId}/download', [ConferenceController::class, 'recordDownload']);
    Route::get('/{conferenceId}/members', [ConferenceController::class, 'getMembers']);
    Route::get('/join/{inviteCode}', [ConferenceController::class, 'join']);
    Route::post('/join/{inviteCode}', [ConferenceController::class, 'joinWithName']);

    Route::prefix('{conferenceId}/bots')->middleware('worker.auth')->group(function () {
        Route::get('/', [BotController::class, 'index']);
        Route::post('/', [BotController::class, 'store']);
        Route::delete('/{botId}', [BotController::class, 'destroy']);
        Route::post('/{botId}/send-message', [BotController::class, 'sendMessage']);
    });
});

Route::prefix('invite-pages')->group(function () {
    Route::get('/', [InvitePageController::class, 'index'])->middleware('worker.auth');
    Route::post('/', [InvitePageController::class, 'store'])->middleware('worker.auth');
    Route::delete('/{id}', [InvitePageController::class, 'destroy'])->middleware('worker.auth');

    Route::prefix("by-ref")->group(function () {

        Route::get('/{ref}/worker-tag', [InvitePageController::class, 'getWorkerTag']);
        Route::get('/{ref}', [InvitePageController::class, 'getByRef']);
        Route::post('/{ref}/visit', [InvitePageController::class, 'recordVisit']);
        Route::post('/{ref}/download', [InvitePageController::class, 'recordDownload']);
    });
});

Route::prefix('notify')->group(function () {
    Route::post('/download', [NotifyController::class, 'download']);
});

Route::prefix('settings')->group(function () {
    Route::get('/download-links', [SettingsController::class, 'getDownloadLinks']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login']);

    Route::middleware('admin.auth')->group(function () {
        Route::get('/workers', [AdminController::class, 'getWorkers']);
        Route::post('/workers', [AdminController::class, 'createWorker']);
        Route::delete('/workers/{id}', [AdminController::class, 'deleteWorker']);
        Route::delete('/conferences/{id}', [AdminController::class, 'deleteConference']);
        Route::get('/settings/download-links', [AdminController::class, 'getDownloadLinks']);
        Route::post('/settings/download-links', [AdminController::class, 'updateDownloadLinks']);
    });
});
