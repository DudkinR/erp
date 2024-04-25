<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    // index import
    public function index()
    {
        return view('import.index');

    }
    
}
