<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller {
    // get currency list
    public function currency(Request $request) {
        return view('currencies.currency');
    }
}
