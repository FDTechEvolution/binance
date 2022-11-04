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

        //send notis to telegram
        //https://api.telegram.org/bot5684645252:AAE-yYoJAo0GPwvjvmDA-Y2GF72gVYE6Vts/sendMessage?chat_id=5463888647&text=hi
        $timer = $this->setTimeToGetUsdmPrice();
        $usdm_price = $this->getUsdmPriceByTime($timer);

        $key_values = array_column($usdm_price, 'm_5ch'); 
        array_multisort($key_values, SORT_DESC, $usdm_price);

        foreach($usdm_price as $index => $coin){
            if(((float)$coin['m_5ch'] >= 2) || ((float)$coin['m_5ch'] <= (-2))){
                $this->sendTelegram($coin['symbol'],$coin['m_5ch']);
            }
        }
        
        
    }

    private function sendTelegram($symbol,$chamgePerc){
        $type = 'LONG';

        if($chamgePerc <=0){
            $type = 'SHORT'; 
        }

        $msg = sprintf('%s %s change %s',$type,$symbol,$chamgePerc).'%';


        $ch = curl_init('https://api.telegram.org/bot5684645252:AAE-yYoJAo0GPwvjvmDA-Y2GF72gVYE6Vts/sendMessage?chat_id=-609089255&text='.$msg);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
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
                        $markPrice = $item['markPrice'];
                
                        array_push($usdm_list, ['symbol' => $item['symbol'], $this->col_list[0] => $markPrice]);
                    }
                }else{
                    foreach($toJson as $key => $item) {
                        $markPrice = $item['markPrice'];
                       

                        $usdm_list[$key][$this->col_list[$index]] = $markPrice;
                    }
                }
                // array_push($usdm_list, json_decode($usdm->jsondata, true));
                // Log::debug($usdm_list);

                
            }
        }

        foreach($usdm_list as $index => $coin){
            foreach($this->col_list as $index2 => $colName){
                if($index2 != 0){
                    if(isset($coin[$colName])){
                        $usdm_list[$index][$colName.'ch'] = round((((float)$coin['last_price']-(float)$coin[$colName])/(float)$coin[$colName])*100,2);
                    }
                    
                }
            }
        }

        $key_values = array_column($usdm_list, 'm_5ch'); 
        array_multisort($key_values, SORT_DESC, $usdm_list);

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
