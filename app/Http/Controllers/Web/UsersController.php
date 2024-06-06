<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\WebController as Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct(
            model: User::class,
        );
    }
}
