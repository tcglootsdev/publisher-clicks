<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return response()->redirectTo('/front/web');
//});

Route::group([], function () {
   Route::group([], __DIR__.'/web/web.php');
});
