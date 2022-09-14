<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request) {
        $data = $request->only('username', 'password', 'name');
        $checkUser = $this->checkUser($data['username']);

        if(!$checkUser){
            $user = User::create([
                "username" => $data['username'],
                "password" => Hash::make($data['password']),
                "name" => $data['name']
            ]);
    
            if($user) return response()->json(['msg' => 'เพิ่มผู้ใช้งานเรียบร้อย', 'error' => NULL], 202);
        }else{
            return response()->json(['error' => 'username ซ้ำ'], 200);
        }

        return response()->json(['error' => 'เกิดข้อผิดพลาด...'], 200);
    }

    private function checkUser($username) {
        $user = User::where('username', $username)->first();
        if(isset($user)) return true;
        return false;
    }
}
