<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConferenceController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\InvitePageController;
use App\Http\Controllers\Api\NotifyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkerController;
use App\Http\Controllers\Api\BotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Settings routes
Route::get('/settings/download-links', [SettingsController::class, 'getDownloadLinks']);

// Auth routes
Route::post('/auth/login', [AuthController::class, 'login']);

// Worker routes
Route::get('/workers/me', [WorkerController::class, 'me'])->middleware('worker.auth');
Route::get('/workers', [WorkerController::class, 'index']);

// Conference routes
Route::get('/conferences', [ConferenceController::class, 'index'])->middleware('worker.auth');
Route::post('/conferences', [ConferenceController::class, 'store'])->middleware('worker.auth');
Route::delete('/conferences/{id}', [ConferenceController::class, 'destroy'])->middleware('worker.auth');
Route::get('/conferences/{conferenceId}/worker-tag', [ConferenceController::class, 'getWorkerTag']);
Route::post('/conferences/{conferenceId}/visit', [ConferenceController::class, 'recordVisit']);
Route::get('/conferences/{conferenceId}/messages', [ConferenceController::class, 'getMessages']);
Route::post('/conferences/{conferenceId}/messages', [ConferenceController::class, 'sendMessage']);
Route::post('/conferences/{conferenceId}/download', [ConferenceController::class, 'recordDownload']);
Route::get('/conferences/{conferenceId}/members', [ConferenceController::class, 'getMembers']);
Route::get('/conferences/join/{inviteCode}', [ConferenceController::class, 'join']);
Route::post('/conferences/join/{inviteCode}', [ConferenceController::class, 'joinWithName']);

// Bot routes
Route::get('/conferences/{conferenceId}/bots', [BotController::class, 'index'])->middleware('worker.auth');
Route::post('/conferences/{conferenceId}/bots', [BotController::class, 'store'])->middleware('worker.auth');
Route::delete('/conferences/{conferenceId}/bots/{botId}', [BotController::class, 'destroy'])->middleware('worker.auth');
Route::post('/conferences/{conferenceId}/bots/{botId}/send-message', [BotController::class, 'sendMessage'])->middleware('worker.auth');

// Invite page routes
Route::get('/invite-pages', [InvitePageController::class, 'index'])->middleware('worker.auth');
Route::post('/invite-pages', [InvitePageController::class, 'store'])->middleware('worker.auth');
Route::delete('/invite-pages/{id}', [InvitePageController::class, 'destroy'])->middleware('worker.auth');
Route::get('/invite-pages/by-ref/{ref}/worker-tag', [InvitePageController::class, 'getWorkerTag']);
Route::get('/invite-pages/by-ref/{ref}', [InvitePageController::class, 'getByRef']);
Route::post('/invite-pages/by-ref/{ref}/visit', [InvitePageController::class, 'recordVisit']);
Route::post('/invite-pages/by-ref/{ref}/download', [InvitePageController::class, 'recordDownload']);

// Notify routes
Route::post('/notify/download', [NotifyController::class, 'download']);
Route::post('/admin/login', [AdminController::class, 'login']);
// Admin routes (protected by AdminAuth middleware)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    
    Route::get('/workers', [AdminController::class, 'getWorkers']);
    Route::get('/settings/download-links', [AdminController::class, 'getDownloadLinks']);
    Route::post('/settings/download-links', [AdminController::class, 'updateDownloadLinks']);
    Route::delete('/worker/{id}', [AdminController::class, 'deleteWorker']);
    Route::delete('/conference/{id}', [AdminController::class, 'deleteConference']);
});