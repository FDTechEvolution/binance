<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index() {
        if(!Auth::check()) return view('auth.login');
        return redirect('features');
    }

    public function authenticate(Request $request) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/features');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('/logout');
    }
    
    public function username() {
        return 'username';
    }
}
