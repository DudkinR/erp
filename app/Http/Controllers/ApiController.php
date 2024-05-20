<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //saveSession
    public function saveSession(Request $request)
    {
        $name_session = $request->ns;
        $value_session = $request->vs;
        $request->session()->put($name_session, $value_session);
        return response()->json(['status' => 'success', $name_session=>$value_session , 'message' => 'Session saved']);
    }
}
