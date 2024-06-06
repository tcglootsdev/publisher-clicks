<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return response()->redirectTo('/front/web/admin');
//});

Route::group([], function () {
    Route::group(['prefix' => 'admin'], __DIR__.'/admin.php');
});
