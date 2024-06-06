<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'web'], function () {
    Route::group(['prefix' => 'auth'], __DIR__.'/auth.php');
    Route::group(['prefix' => 'users'], __DIR__.'/users.php');
    Route::group(['prefix' => 'clicks'], __DIR__.'/clicks.php');
});
