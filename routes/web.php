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
    return view('welcome');
});

// Todo Web Routes
Route::resource('todos', \App\Http\Controllers\TodoController::class);

// Email Logs Web Routes
Route::get('/email-logs', [\App\Http\Controllers\EmailLogController::class, 'index'])->name('email-logs.index');
