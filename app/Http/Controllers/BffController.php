<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BffController extends Controller
{
    public function __invoke(Request $request, $placeholder)
    {
        // return $request->all();

        // Http::acceptJson()
        //     ->withToken('token')
        //     ->post(/* ... */);
    }
}
