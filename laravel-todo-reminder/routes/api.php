<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Todo API Routes
Route::apiResource('todos', \App\Http\Controllers\TodoController::class);

// Email Logs API Routes
Route::apiResource('email-logs', \App\Http\Controllers\EmailLogController::class)->only(['index', 'show']);
