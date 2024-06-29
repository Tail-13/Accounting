<?php

use App\Http\Controllers\AccountRestController;
use App\Http\Controllers\AccountTypeRestController;
use App\Http\Controllers\JournalRestController;
use App\Http\Controllers\LoginRestController;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [LoginRestController::class, 'login']);


Route::middleware('check.access_token')->group(function(){
    Route::prefix('account')->group(function () {
        Route::prefix("type")->group(function () {
            Route::get("", [AccountTypeRestController::class, 'get']);
        });
        Route::get("", [AccountRestController::class, 'get']);
    });
    Route::prefix('journal')->group(function () {
        Route::get("", [JournalRestController::class, "get"]);
        Route::post("", [JournalRestController::class, "create"]);
    });

    Route::get("role", [LoginRestController::class, "checkRole"]);
    Route::delete('logout', [LoginRestController::class, 'logout']);
});

