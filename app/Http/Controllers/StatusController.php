<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;

use App\Models\Feature;

class StatusController extends Controller
{
    public function index() {
        $coins = Feature::select('coin_name')->where('status', 'WATCH')->orWhere('status', 'OPEN')->groupBy('coin_name')->get();
        $coin_all = Feature::select('coin_name')->groupBy('coin_name')->get();

        $status = $this->getCoinStatus($coins);
        $status_all = $this->getCoinStatus($coin_all);

        return view('status.index', ['coins' => $status, 'all' => $status_all]);
    }

    public function setCloseStatus($coin) {
        Feature::where('coin_name', $coin)->update(['status' => 'CLOSE']);

        return redirect()->back();
    }

    private function getCoinStatus($coins) {
        $coin_status = [];
        foreach($coins as $coin) {
            $response = Http::get('https://api.binance.com/api/v3/avgPrice?symbol='.strtoupper($coin->coin_name).'USDT');
            $res = json_decode($response->getBody()->getContents(), true);

            if(isset($res['code'])) array_push($coin_status, ['coin' => $coin->coin_name, 'status' => $res['msg']]);
            else array_push($coin_status, ['coin' => $coin->coin_name, 'status' => true]);
        }

        return $coin_status;
    }
}
