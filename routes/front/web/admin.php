<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return response()->redirectTo('/front/web/admin/auth/signin');
//});

Route::middleware('webauth')->group(function () {
    Route::get('/auth/signin', function () {
        return view('web.admin.pages.signin');
    });
});

