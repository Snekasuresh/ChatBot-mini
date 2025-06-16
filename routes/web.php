<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', [ChatController::class, 'index']);         // For loading the UI
Route::post('/chat', [ChatController::class, 'chat'])->name('chat');  // For handling message
