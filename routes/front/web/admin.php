<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Utils;

//Route::get('/', function () {
//    return response()->redirectTo('/front/web/admin/auth/signin');
//});

Route::get('/', function () {
    return response()->redirectTo('/admin/auth/signin');
});

Route::middleware('webauth')->group(function () {
    Route::get('/auth/signin', function () {
        return view('web.admin.pages.signin');
    });

    Route::get('/dashboard', function () {
        return view('web.admin.pages.dashboard', ['title' => 'Dashboard']);
    });

    Route::get('/publishers', function () {
        return view('web.admin.pages.publishers', ['title' => 'Publishers']);
    });
});

Route::middleware('webauth:/admin/publishers/{id}/clicks')->get('/publishers/{id}/clicks', function (Request $request, $id) {
    if (!User::where(['id' => $id, 'role' => 'publisher'])->first()) {
        return Utils::responseJsonError(trans('models/user.invalid_id'));
    }
    return view('web.admin.pages.clicks', ['title' => 'Clicks', 'data' => ['user_id' => $id]]);
});
