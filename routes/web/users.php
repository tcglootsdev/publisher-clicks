<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;

Route::middleware('webauth')->group(function () {
    Route::get('/', [UsersController::class, 'get']);
});
