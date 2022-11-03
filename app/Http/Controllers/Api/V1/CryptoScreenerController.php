<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

use App\Models\UsdmPrice;

class CryptoScreenerController extends Controller
{
    private $setTime = ['0 minute', '-2 minute', '-5 minute', '-10 minute', '-30 minute', '-1 hour', '-4 hour', '-24 hour'];
    private $col_list = ['last_price', 'm_2', 'm_5', 'm_10', 'm_30', 'h_1', 'h_4', 'h_24'];
    private $col_test = ['data1', 'data2'];

    public function getPremiumIndex() {
        $response = Http::get(RouteServiceProvider::BINANCE_FAPI.'/premiumIndex');
        UsdmPrice::create([
            'jsondata' => $response->getBody()->getContents(),
            'time' => date('Y-m-d H:i')
        ]);
    }

    public function getCryptoScreener() {
        $timer = $this->setTimeToGetUsdmPrice();
        // $timer = ['2022-11-03 14:37:00', '2022-11-03 14:45:00'];
        $usdm_price = $this->getUsdmPriceByTime($timer);
        // $this->setDataUsdmPrice($usdm_price);
        return response()->json(['error' => null, 'data' => $usdm_price], 200);
    }

    private function setTimeToGetUsdmPrice() {
        $now = date("Y-m-d H:i");
        $getTime = [];

        foreach($this->setTime as $time) {
            $newTime = date('Y-m-d H:i', strtotime($time, strtotime($now)));
            array_push($getTime, $newTime);
        }

        return $getTime;
    }

    private function getUsdmPriceByTime($timer) {
        $usdm_list = [];
        foreach($timer as $index => $time) {
            $usdm = UsdmPrice::where('time', $time)->first();
            if(isset($usdm)) {
                $toJson = json_decode($usdm->jsondata, true);
                
                if($index == 0) {
                    foreach($toJson as $item) {
                        array_push($usdm_list, ['symbol' => $item['symbol'], $this->col_list[0] => $item['markPrice']]);
                    }
                }else{
                    foreach($toJson as $key => $item) {
                        $usdm_list[$key][$this->col_list[$index]] = $item['markPrice'];
                    }
                }
                // array_push($usdm_list, json_decode($usdm->jsondata, true));
            }
        }

        return $usdm_list;
    }

    private function setDataUsdmPrice($data) {
        foreach($data as $index => $item) {
            
            Log::debug($item);
            // foreach($this->col_test as $col) {
            //     Log::debug($item[$col]);
            // }
        }
    }
}
