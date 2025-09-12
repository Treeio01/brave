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

Route::get('/admin', function () {
    return view('index');
});

Route::get('/admin/login', function () {
    return view('index');
});

Route::get('/admin/dashboard', function () {
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

Route::get('/worker/conference/{id}', function ($id) {
    return view('index');
});
