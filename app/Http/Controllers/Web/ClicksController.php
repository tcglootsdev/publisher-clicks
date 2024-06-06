<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Utils;
use App\Http\Controllers\Web\WebController as Controller;
use App\Models\Click;
use Illuminate\Http\Request;

class ClicksController extends Controller
{
    public function __construct()
    {
        parent::__construct(
            model: Click::class,
        );
    }

    public function getStatistic(Request $request)
    {
        $number = [];
        $number['clicks'] = Click::count();
        return Utils::responseJsonData([
            'number' => $number
        ]);
    }
}
