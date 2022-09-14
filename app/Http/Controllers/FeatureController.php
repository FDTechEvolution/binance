<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\Feature;

class FeatureController extends Controller
{
    public function index() {
        // $features = Feature::get();
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
        return view('features.index', ['features' => $features]);
    }

    public function create(Request $request) {
        $checkCoinName = $this->checkCoinName($request->coin, $request->type, $request->status);
        $checkBinance = $this->checkCoinNameBinance($request->coin);

        if(!$checkCoinName) {
            if($checkBinance) {
                $feature = Feature::create([
                            'coin_name' => strtoupper($request->coin),
                            'type' => $request->type,
                            'entry1' => $request->entry1,
                            'entry2' => $request->entry2,
                            'entry3' => $request->entry3,
                            'target1' => $request->target1,
                            'target2' => $request->target2,
                            'target3' => $request->target3,
                            'stop_loss' => $request->stop_loss,
                            'avg_price' => $request->avg_price,
                            'usdt_pnl' => $request->usdt_pnl,
                            'docdate' => $request->docdate,
                            'status' => $request->status,
                            'description' => $request->description
                        ]);

                if($feature) return redirect()->back();
            }
            else return redirect()->back()->with('warning', 'ไม่มีเหรียญ '.strtoupper($request->coin).' นี้ใน Binance'); 
        }
        else return redirect()->back()->with('warning', 'ชื่อเหรียญ '.strtoupper($request->coin).' (Type : '.$request->type.') ซ้ำกับในฐานข้อมูล');

        return redirect()->back()->with('error', 'เกิดข้อผิดพลาดบางอย่าง...');
    }

    private function checkCoinName($coin, $type, $status, $id = null) {
        if($id == null) {
            $check = Feature::where('coin_name', $coin)->first();
            if(!$check) return false;
            else {
                if($check->status == $status) return true;
                else {
                    if($check->type == $type) return true;
                    return false;
                }
            }
        }else{
            $check = Feature::where('coin_name', $coin)->where('id', '!=', $id)->first();
            if(!$check) return false;
            else {
                if($status != 'CLOSE' && $status != 'STOPLOSS') {
                    if($check->status == $status) return true;
                    else {
                        if($check->type == $type) return true;
                        return false;
                    }
                }
                return false;
            }
        }
        return true;
    }

    private function checkCoinNameBinance($coin) {
        $response = Http::get('https://api.binance.com/api/v3/avgPrice?symbol='.strtoupper($coin).'USDT');
        $res = json_decode($response->getBody()->getContents(), true);

        if(isset($res['code'])) return false;
        return true;
    }

    public function update(Request $request) {
        $checkCoinName = $this->checkCoinName($request->coin, $request->type, $request->status, $request->e_id);
        $checkBinance = $this->checkCoinNameBinance($request->coin);

        if(!$checkCoinName) {
            if($checkBinance) {
                $feature = Feature::find($request->e_id);
                $feature->update([
                    'coin_name' => strtoupper($request->coin),
                    'type' => $request->type,
                    'entry1' => $request->entry1,
                    'entry2' => $request->entry2,
                    'entry3' => $request->entry3,
                    'target1' => $request->target1,
                    'target2' => $request->target2,
                    'target3' => $request->target3,
                    'stop_loss' => $request->stop_loss,
                    'avg_price' => $request->avg_price,
                    'usdt_pnl' => $request->usdt_pnl,
                    'docdate' => $request->docdate,
                    'status' => $request->status,
                    'description' => $request->description
                ]);

                if($feature) return redirect()->back();
            }
            else return redirect()->back()->with('warning', 'ไม่มีเหรียญ '.strtoupper($request->coin).' นี้ใน Binance'); 
        }else return redirect()->back()->with('warning', 'ชื่อเหรียญ '.strtoupper($request->coin).' (Type : '.$request->type.') ซ้ำกับในฐานข้อมูล');

        return redirect()->back()->with('error', 'เกิดข้อผิดพลาดบางอย่าง...');
    }

    public function delete(Request $request) {
        Feature::find($request->id)->delete();
        return redirect()->back();
    }
}
