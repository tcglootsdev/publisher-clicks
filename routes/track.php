<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Click;

Route::get('/', function(Request $request) {
    $publisher = User::where([
        'id' => $request->query('publisher'),
        'role' => 'publisher'
    ])->first();
    if ($publisher) {
        Click::create(['user_id' => $publisher->id]);
    }
    return redirect('/welcome');
});
