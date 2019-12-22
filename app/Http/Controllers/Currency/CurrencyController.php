<?php


namespace App\Http\Controllers\Currency;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{

    /**
     * @param Request $request
     * @param $exchangeFrom
     * @param $exchangeTo
     * @return bool
     * @author Lasha Lomidze <lomidzelf@gmail.com>
     */
    public function analyseExchangeWays(Request $request, $exchangeFrom, $exchangeTo)
    {
        return true;
    }
}
