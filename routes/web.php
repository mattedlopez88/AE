<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/logout', [AuthController::class, 'logout']);
    Route::get('/api/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // region User
//    Route::get('/users', UserController::class, 'index')->name('users.index');
    Route::resource('users', UserController::class);
    // endregion
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
