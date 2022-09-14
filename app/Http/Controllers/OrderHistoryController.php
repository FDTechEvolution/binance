<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\OrderHistory;

class OrderHistoryController extends Controller
{
    public function index() {
        $now = strtotime('now').'000';
        $from = strtotime(date('d-m-Y', substr($now, 0, -3))).'000';
        $dateNow = date('Y-m-d', substr($now, 0, -3));
        $order_history = OrderHistory::whereBetween('time', [$from, $now])->orderBy('time', 'DESC')->get();
        
        $is_history = $this->historyFunc($order_history);

        return view('history.index', ['histories' => $is_history, 'date_now' => $dateNow, 'date_from' => '', 'date_to' => '', 'type' => 'day']);
    }

    public function getHistory(Request $request) {
        $date_now = '';
        $date_from = '';
        $date_to = '';
        if($request->type == 'day') {
            if(isset($request->goto)) {
                $ex_date = explode('-', $request->date);
                $th_date = $ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0];
                if($request->goto == 'prev') {
                    $to = strtotime($th_date).'000';
                    $from = strtotime($th_date.'-1 day').'000';
                    $date_now = date('Y-m-d', substr($from, 0, -3));
                }else if($request->goto == 'next') {
                    $to = strtotime($th_date.'+2 day').'000';
                    $from = strtotime($th_date.'+1 day').'000';
                    $date_now = date('Y-m-d', substr($from, 0, -3));
                }
            }else{
                $ex_date = explode('-', $request->date);
                $to = strtotime($ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0].'+1 day').'000';
                $from = strtotime($ex_date[2].'-'.$ex_date[1].'-'.$ex_date[0]).'000';
                $date_now = $request->date;
            }
        }
        else if($request->type == 'some') {
            $ex_date_from = explode('-', $request->date_from);
            $ex_date_to = explode('-', $request->date_to);

            $from = strtotime($ex_date_from[2].'-'.$ex_date_from[1].'-'.$ex_date_from[0]).'000';
            $to = strtotime($ex_date_to[2].'-'.$ex_date_to[1].'-'.$ex_date_to[0].'+1 day').'000';
            $date_from = $request->date_from;
            $date_to = $request->date_to;
        }

        $order_history = OrderHistory::whereBetween('time', [$from, $to])->orderBy('time', 'DESC')->get();

        $is_history = $this->historyFunc($order_history);

        return view('history.index', ['histories' => $is_history, 'date_now' => $date_now, 'date_from' => $date_from, 'date_to' => $date_to, 'type' => $request->type]);
    }

    private function historyFunc($order_history) {
        $sum = 0;
        $history = [];

        foreach($order_history as $order) {
            if(empty($history)) array_push($history, ['symbol' => $order['symbol'], 'type' => $order['income_type'], 'time' => strtotime(date('d-m-Y', substr($order['time'], 0, -3))), 'income' => $order['income']]);
            else {
                $symbol = $order['symbol'];
                $incomeType = $order['income_type'];
                $dateTime = strtotime(date('d-m-Y', substr($order['time'], 0, -3)));

                $filtered_array = array_filter($history, function($val) use($symbol, $incomeType, $dateTime){
                    return ($val['symbol'] == $symbol and $val['type'] == $incomeType and $val['time'] == $dateTime);
                });

                if($filtered_array) {
                    foreach($filtered_array as $key => $fil) {
                        $history[$key]['income'] += $order['income'];
                    }
                } else array_push($history, ['symbol' => $order['symbol'], 'type' => $order['income_type'], 'time' => $dateTime, 'income' => $order['income']]);
            }
        }

        $by_time = array_column($history, 'time');
        array_multisort($by_time, SORT_DESC, $history);
        
        $is_history = $this->combineHistory($history, $this->getSumHistory($history));
        return $is_history;
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

}
