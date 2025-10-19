<?php

namespace App\Http\Controllers;

use App\Models\Pengaturanumum;
use Illuminate\Http\Request;

class WagatewayController extends Controller
{
    public function index()
    {
        $data['generalsetting'] = Pengaturanumum::where('id', 1)->first();
        return view('wagateway.scanqr', $data);
    }
}
