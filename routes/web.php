<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/room/{inviteCode}', function ($inviteCode) {
    return view('index');
});

Route::get('/worker/login', function () {
    return view('index');
});

Route::get('/worker/dashboard', function () {
    return view('index');
});

// Admin routes
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');

// Admin worker management
Route::get('/admin/workers/create', [App\Http\Controllers\AdminController::class, 'createWorker'])->name('admin.worker.create');
Route::post('/admin/workers', [App\Http\Controllers\AdminController::class, 'storeWorker'])->name('admin.worker.store');
Route::delete('/admin/workers/{id}', [App\Http\Controllers\AdminController::class, 'deleteWorker'])->name('admin.worker.delete');

// Admin conference management
Route::delete('/admin/conferences/{id}', [App\Http\Controllers\AdminController::class, 'deleteConference'])->name('admin.conference.delete');

// Admin settings
Route::post('/admin/settings/download-links', [App\Http\Controllers\AdminController::class, 'updateDownloadLinks'])->name('admin.settings.download-links');

Route::get('/join/{inviteCode}', function ($inviteCode) {
    return view('index');
});

Route::get('/{inviteCode}', function ($inviteCode) {
    return view('index');
});

Route::get('/invite/{ref}', function ($ref) {
    return view('index');
});

Route::get('/worker/conference/{id}', function ($id) {
    return view('index');
});
