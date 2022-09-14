<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;

use App\Models\Feature;

class FeatureController extends Controller
{
    public function list() {
        $features = DB::table('features')
                        ->orderByRaw("CASE 
                            WHEN status = 'OPEN' THEN 1 
                            WHEN status = 'WATCH' THEN 2 
                            WHEN status = 'CLOSE' THEN 3 
                            WHEN status = 'STOPLOSS' THEN 4 
                            ELSE 5 END ASC
                        ")
                        ->orderBy('docdate', 'DESC')
                        ->get();

        return response()->json(['error' => null, 'data' => $features], 200);
    }

    public function update_avg_price() {
        $symbols = $this->setSymbols();

        if($symbols != false) {
            $response = Http::get(RouteServiceProvider::BINANCE_API.$symbols);
            $res = json_decode($response->getBody()->getContents(), true);

            $this->updateFeatureAVGPrice($res);
        }
    }

    private function setSymbols() {
        $features = Feature::select('coin_name')->where('status', 'WATCH')->orWhere('status', 'OPEN')->groupBy('coin_name')->get();
        $feature_count = $features->count();

        if($feature_count > 0) {
            $symbols = '%5B';
            foreach($features as $key => $feature) {
                $symbols.= $key+1 != $feature_count ? '%22'.$feature->coin_name.'USDT%22,' : '%22'.$feature->coin_name.'USDT%22';
            }

            $symbols.='%5D';

            return $symbols;
        }
        return false;
    }

    private function updateFeatureAVGPrice($response) {
        foreach($response as $res) {
            $coin = explode('USDT', $res['symbol']);
            Feature::where('coin_name', $coin[0])->update([
                'avg_price' => $res['price']
            ]);
        }
    }
}
