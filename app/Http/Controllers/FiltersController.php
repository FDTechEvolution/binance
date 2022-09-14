<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FiltersController extends Controller
{
    public function index() {
        return view('filters.index');
    }
}
