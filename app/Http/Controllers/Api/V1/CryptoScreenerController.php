<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;
use File;

use App\Models\UsdmPrice;

class CryptoScreenerController extends Controller
{
    private $setTime = ['0 minute','-2 minute', '-5 minute', '-10 minute', '-30 minute'];
    //private $setTime = ['0 minute','-2 minute', '-5 minute', '-10 minute', '-30 minute', '-1 hour', '-4 hour', '-24 hour'];
    private $col_list = ['last_price','m_2', 'm_5', 'm_10', 'm_30', 'h_1', 'h_4', 'h_24'];
    private $col_test = ['data1', 'data2'];

    public function getPremiumIndex() {

        sleep($_GET['delay_seconds']);
        
        $response = Http::get(RouteServiceProvider::BINANCE_FAPI.'/premiumIndex');

        $data = $response->getBody()->getContents();

        $dataArr = json_decode($data,true);
        $newDataArr = [];

        foreach($dataArr as $index => $coin){
            $_c = $coin['symbol'];
            if ($this->endsWith($_c, "BUSD") || $this->startsWith($_c,'HIGH')) {
                unset($dataArr[$index]);

                //array_push($newDataArr,['symbol'=>$coin['symbol'],'markPrice'=>$coin['markPrice']]);
            }
        }


        UsdmPrice::create([
            'jsondata' => json_encode($dataArr),
            'time' => date('Y-m-d H:i')
        ]);


        /*
        $fileName = date('Y-m-d H:i') . '.json';
        $fileStorePath = public_path('/files/json/'.$fileName);
  
        File::put($fileStorePath, json_encode($newDataArr));
        */

        $this->indicators();

        return response()->json(['error' => null, 'data' => json_encode($dataArr)], 200);

        
    }

    public function indicators(){
        //send notis to telegram
        //https://api.telegram.org/bot5684645252:AAE-yYoJAo0GPwvjvmDA-Y2GF72gVYE6Vts/sendMessage?chat_id=5463888647&text=hi
        //$timer = $this->setTimeToGetUsdmPrice();

        //return response()->json(['error' => null, 'data' => $timer], 200);

        //$usdm_price = $this->getUsdmPriceByTime($timer);

        $priceDatas = UsdmPrice::orderBy('created_at', 'DESC')->skip(0)->take(100)->get(); 

        $coins = [];


        foreach($priceDatas as $index => $row){
            $jsondata = $row['jsondata'];
            if(!is_null($jsondata) && $jsondata != ''){
                $jsondata = json_decode($jsondata,true);

                foreach($jsondata as $index2 =>$d){
                    $coins[$d['symbol']][$index] = (float)$d['markPrice'];
                }
                
            }
        }


        foreach($coins as $index => $coin){
            $symbol = $index;
            $priceM1 = $coin[0];
            $priceM2 = $coin[1];
            $priceM3 = $coin[2];
            $priceM4 = $coin[3];
            $priceM5 = $coin[4];
            $priceM6 = $coin[5];
            $priceM7 = $coin[6];
            $priceM8 = $coin[7];
            $priceM9 = $coin[8];
            $priceM10 = $coin[9];
            $priceM15 = $coin[14];
            $priceM20 = $coin[19];
            $priceM25 = $coin[24];
            $priceM30 = $coin[29];
            $priceM40 = $coin[39];
            $priceM60 = $coin[59];
            $priceM80 = $coin[79];
            $priceM100 = $coin[99];

            $change2Min = round((($priceM1-$priceM3)/$priceM3)*100,2);

            $change5Min = round((($priceM1-$priceM8)/$priceM8)*100,2);

            if($symbol =='BTCUSDT'){
                if(($change2Min >= 0.3) && ($priceM2 > $priceM3) && ($priceM3 > $priceM4)){
                    $msg = sprintf('%s,LONG %s%% in 1min',$symbol,$change2Min);

                    $this->sendTelegram($change2Min,$msg);

                }elseif(($change5Min >= 0.3) &&($priceM1 > $priceM2) && ($priceM2 > $priceM3)){
                    $msg = sprintf('%s,LONG %s%% in 5min',$symbol,$change5Min);
                    $this->sendTelegram($change2Min,$msg);
                }elseif(($change2Min <= -0.3) &&($priceM1<$priceM2) && ($priceM2 < $priceM3)){
                    $msg = sprintf('%s,SHORT %s%% in 2min',$symbol,$change2Min);
                    $this->sendTelegram($change2Min,$msg);
                }

            }else{
                if(($change2Min >= 1)  && ($priceM2 > $priceM3) && ($priceM3 > $priceM4) && ($priceM4 > $priceM5)){
                    $msg = sprintf('%s,LONG %s%% in 1min',$symbol,$change2Min);

                    $this->sendTelegram($change2Min,$msg);

                }elseif(($change5Min >=1.5) && ($priceM1 > $priceM3) && ($priceM1 > $priceM5) && ($priceM2 > $priceM7) && ($priceM3 > $priceM8) && ($priceM3 > $priceM20) && ($priceM3 > $priceM40) && ($priceM3 > $priceM80) && ($priceM3 > $priceM100)){
                    $msg = sprintf('%s,LONG trading %s%% in 5min',$symbol,$change5Min);
                    //$this->sendTelegram($change5Min,$msg);



                }elseif(($change2Min <= -1) && ($priceM2 < $priceM3) && ($priceM3 < $priceM4) && ($priceM4 < $priceM5)){
                    $msg = sprintf('%s,SHORT %s%% in 2min',$symbol,$change2Min);
                    $this->sendTelegram($change2Min,$msg);
                }elseif(($change5Min <= -1.5) && ($priceM1 < $priceM3) && ($priceM1 < $priceM5) && ($priceM2 < $priceM7) && ($priceM3 < $priceM8) && ($priceM3 < $priceM20) && ($priceM3 > $priceM40) && ($priceM3 < $priceM80) && ($priceM3 < $priceM100)){
                    $msg = sprintf('%s,SHORT %s%% in 5min',$symbol,$change5Min);
                    //$this->sendTelegram($change5Min,$msg);
                }
            }


            

        }

    

        return response()->json(['error' => null, 'data' => $coins], 200);


    }

    private function sendTelegram($chamgePerc,$msg = ''){
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

            

            
        }

        return $usdm_list;


        //cal %
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

    private function endsWith($string, $endString)
    {
      $len = strlen($endString);
      if ($len == 0) {
        return true;
      }
      return substr($string, -$len) === $endString;
    }

    private function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
