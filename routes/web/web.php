<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'web'], function () {
    Route::group(['prefix' => 'auth'], __DIR__.'/auth.php');
});
