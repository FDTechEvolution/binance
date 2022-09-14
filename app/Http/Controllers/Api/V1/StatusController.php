<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;

use App\Models\Feature;

class StatusController extends Controller
{
    public function coin() {
        $coin = Feature::select('coin_name')->where('status', 'WATCH')->orWhere('status', 'OPEN')->groupBy('coin_name')->get();

        return response()->json(['error' => null, 'data' => $coin], 200);
    }
}
