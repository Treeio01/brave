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

Route::get('/join/{inviteCode}', function ($inviteCode) {
    return view('index');
});

Route::get('/{inviteCode}', function ($inviteCode) {
    return view('index');
});

Route::get('/invite/{ref}', function ($ref) {
    return view('index');
});



Route::prefix("/worker")->group(function () {
    Route::get('/login', function () {
        return view('index');
    });
    Route::get('/dashboard', function () {
        return view('index');
    });

    Route::get('/conference/{id}', function ($id) {
        return view('index');
    });

});


Route::prefix("admin")->group(function () {

    Route::get('/', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
    Route::delete('/conferences/{id}', [App\Http\Controllers\AdminController::class, 'deleteConference'])->name('admin.conference.delete');
    Route::post('/settings/download-links', [App\Http\Controllers\AdminController::class, 'updateDownloadLinks'])->name('admin.settings.download-links');

    Route::prefix("workers")->group(function () {
        Route::get('/create', [App\Http\Controllers\AdminController::class, 'createWorker'])->name('admin.worker.create');
        Route::post('/', [App\Http\Controllers\AdminController::class, 'storeWorker'])->name('admin.worker.store');
        Route::delete('/{id}', [App\Http\Controllers\AdminController::class, 'deleteWorker'])->name('admin.worker.delete');

    });
});
