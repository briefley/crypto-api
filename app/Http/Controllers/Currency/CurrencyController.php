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
     * @return false|string
     * @author Lasha Lomidze <lomidzelf@gmail.com>
     * Entry point function routed to by an API route.
     */
    public function analyseExchangeWays(Request $request, $exchangeFrom, $exchangeTo)
    {
        $validationResult = $this->validateRequestBody($request);
        $exchangeFrom = strtolower($exchangeFrom);
        $exchangeTo = strtolower($exchangeTo);
        return json_encode($validationResult);
    }

    /**
     * @param $request
     * @return array
     * @author Lasha Lomidze <lomidzelf@gmail.com>
     * Handles the validation of the API request body parameter.
     * Firstly, checks if the request body can be converted to JSON,
     * then moves to validation by rules, so all required parameters are present.
     */
    private function validateRequestBody($request)
    {
        $rules = [
            '*' => 'required',
            '*.currencyFrom' => 'required|string',
            '*.currencyTo' => 'required|string',
        ];

        if (!$request->json()->all()) {
            return [
                'result' => false,
                'errors' => 'Invalid JSON Passed'
            ];
        }

        $validator = Validator::make($request->json()->all(), $rules);

        if ($validator->passes()) {
            return [
                'result' => true,
                'errors' => null
            ];
        }

        return [
            'result' => false,
            'errors' => $validator->errors()->toJson()
        ];

    }
}
