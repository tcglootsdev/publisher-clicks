<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ClicksController;

Route::middleware('webauth')->group(function () {
    Route::get('/', [ClicksController::class, 'get']);
    Route::get('/statistic', [ClicksController::class, 'getStatistic']);
});
