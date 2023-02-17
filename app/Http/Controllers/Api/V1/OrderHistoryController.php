<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\OrderHistory;

class OrderHistoryController extends Controller
{
    public function getOrder() {
        // $apiKey = 'VACCYl1MGhz26mydgL6aLh9pEM6ra3w6gboq3azLK3EUCcOkXCc64KuneIF593RK';
        // $secretKey = 'ck4SNw4hMB9gu42mUnhRfOPoc583hLUY1IJ3a6OoWK6q2eZ5EZmejHZQj5yb35mL';

        $apiKey = '06DdLQZsreFXf726eD5EqmxomP9RWYpz8VHdmqFPLVcCVyCDhoK0Fc27CbXHoofw';
        $secretKey = 'HlYXRHQo2HV6BxdVTDUF6xntn4izHJ0RVfT71pcPo5vODxOl6rvkmasG4YBc5V0B';

        $start = strtotime('-3 Hour').'000';
        $end = strtotime('now').'000';
        $query_string = 'timestamp='.round(microtime(true) * 1000).'&limit=1000&startTime='.$start.'&endTime='.$end; 
        $signature = hash_hmac('sha256', $query_string, $secretKey); 
        $timestamp = round(microtime(true) * 1000);

        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $apiKey
        ])->get('https://fapi.binance.com/fapi/v1/income?timestamp='.$timestamp.'&limit=1000&startTime='.$start.'&endTime='.$end.'&signature='.$signature);

        $res = json_decode($response->getBody()->getContents(), true);
        $row = $this->updateToDatabase($res);

        return response()->json(['error' => null, 'row' => $row], 200);
    }

    public function updateToDatabase($data) {
        $row = 0;
        foreach($data as $item) {
            $row++;
            $order = OrderHistory::where('tran_id', $item['tranId'])->where('income_type', $item['incomeType'])->where('time', $item['time'])->first();
            if(!$order) {
                OrderHistory::create([
                    'symbol' => $item['symbol'],
                    'income_type' => $item['incomeType'],
                    'income' => $item['income'],
                    'asset' => $item['asset'],
                    'time' => $item['time'],
                    'info' => $item['info'],
                    'tran_id' => $item['tranId'],
                    'trade_id' => $item['tradeId']
                ]);
            }
        }
        return $row;
    }

    public function index() {
        $income = $this->getIncomeHistory();
        $history = [];

        if($income != false) {
            foreach($income as $inc) {
                if(empty($history)) array_push($history, ['symbol' => $inc['symbol'], 'type' => $inc['incomeType'], 'time' => date('d-m-Y', substr($inc['time'], 0, -3)), 'income' => $inc['income']]);
                else {
                    $symbol = $inc['symbol'];
                    $incomeType = $inc['incomeType'];
                    $incomePrice = $inc['income'];
                    $dateTime = date('d-m-Y', substr($inc['time'], 0, -3));

                    $filtered_array = array_filter($history, function($val) use($symbol, $incomeType, $dateTime){
                        return ($val['symbol'] == $symbol and $val['type'] == $incomeType and $val['time'] == $dateTime);
                    });

                    if($filtered_array) {
                        array_filter($filtered_array, function($arr) use($incomePrice) {
                            $arr['income'] += $incomePrice;
                        });
                    } else array_push($history, ['symbol' => $inc['symbol'], 'type' => $inc['incomeType'], 'time' => $dateTime, 'income' => $inc['income']]);
                }
            }

            $by_symbol = array_column($history, 'symbol');
            array_multisort($by_symbol, SORT_ASC, $history);
            
            $is_history = $this->combineHistory($history, $this->getSumHistory($history));

            return view('history.index', ['histories' => $is_history]);
        }

    }

    private function combineHistory($history, $his_sum) {
        array_push($history, ['symbol' => '#^%UTY#']);
        $arr = [];
        $row = 0;
        $symbol = '';

        foreach($history as $his) {
            if($his['symbol'] != '') {
                if($symbol == $his['symbol']) array_push($arr, $his);
                else{
                    if($symbol != '') {
                        array_push($arr, $his_sum[$row]);
                        array_push($arr, $his);
                        $row++;
                    }else array_push($arr, $his);
                    $symbol = $his['symbol'];
                }
            }
        }

        $num = count($arr);
        unset($arr[$num-1]);

        return $arr;
    }

    private function getIncomeHistory() {
        $apiKey = 'VACCYl1MGhz26mydgL6aLh9pEM6ra3w6gboq3azLK3EUCcOkXCc64KuneIF593RK';
        $secretKey = 'ck4SNw4hMB9gu42mUnhRfOPoc583hLUY1IJ3a6OoWK6q2eZ5EZmejHZQj5yb35mL';

        $query_string = 'timestamp='.round(microtime(true) * 1000).'&limit=1000'; 
        $signature = hash_hmac('sha256', $query_string, $secretKey); 
        $timestamp = round(microtime(true) * 1000);

        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $apiKey
        ])->get('https://fapi.binance.com/fapi/v1/income?timestamp='.$timestamp.'&limit=1000&signature='.$signature);

        $res = json_decode($response->getBody()->getContents(), true);

        if(isset($res)) return $res;
        return false;
    }

    private function getSumHistory($history) {
        array_push($history, ['symbol' => '#-YUHS', 'income' => '00000']);
        $symbol = '';
        $sum = 0;
        $arr_sum = [];

        foreach($history as $his) {
            if($his['symbol'] != '') {
                if($symbol == $his['symbol']) $sum += $his['income'];
                else {
                    if($symbol != '') array_push($arr_sum, ['symbol' => $symbol, 'type' => 'sum', 'sum' => $sum]);
                    $symbol = $his['symbol'];
                    $sum = 0;
                    $sum += $his['income'];
                }
            }
        }
        return $arr_sum;
    }


    public function getCoin() {
        $now = strtotime('now').'000';
        $from = strtotime(date('d-m-Y', substr($now, 0, -3))).'000';

        $history = OrderHistory::where('symbol', 'C98USDT')->whereBetween('time', [$from, $now])->orderBy('time', 'DESC')->get();
        $arr = [];
        $a_time = [];
        $sum = 0;
        
        foreach($history as $his) {
            $sum += $his['income'];
            array_push($a_time, date('d-m-Y H:i:s', substr($his['time'], 0, -3)));
            array_push($arr, ['symbol' => $his['symbol'], 'time' => date('Y-m-d', substr($his['time'], 0, -3)), 'income' => $his['income']]);
        }

        $xxx = $this->getSumHistory($arr);
        
        return response()->json(['data' => $xxx, 'sum' => $sum, 'now' => date('d-m-Y H:i:s', substr($now, 0, -3)), 'from' => date('d-m-Y H:i:s', substr($from, 0, -3)), 'a_time' => $a_time]);
    }
}
