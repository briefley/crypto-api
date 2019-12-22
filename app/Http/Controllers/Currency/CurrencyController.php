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
        if ($validationResult['result']) {
            $exchangeWaysArr = $request->json()->all();
            $result = [];
            $trackedWays = [];

            $this->findExchangeWays($exchangeWaysArr, $exchangeFrom, $exchangeTo, $result, $trackedWays, $totalResults);
            $totalResults = $this->removeDuplicatePaths($totalResults);
            return json_encode($totalResults);
        }

        return json_encode($validationResult);
    }

    /**
     * @param $exchangeWaysArr
     * @param $exchangeFrom
     * @param $exchangeTo
     * @param $result
     * @param $trackedWays
     * @param $totalResults
     * @author Lasha Lomidze <lomidzelf@gmail.com>
     * Uses backtracking algorithm to find all possible currency exchange ways
     * in a provided dataset.
     */
    private function findExchangeWays($exchangeWaysArr, $exchangeFrom, $exchangeTo, $result, $trackedWays, &$totalResults)
    {
        /*
         * Checks if the final destination is reached
         * and saves it in an array passed by reference.
         */
        if ($exchangeFrom == $exchangeTo) {
            $totalResults[] = $result;
            return;
        }

        /*
         * Loops through all provided exchange way pairs
         * of currencies. Essentially moves in breadth oriented way.
         */
        for ($i = 0; $i < count($exchangeWaysArr); $i++) {
            /*
             * Checks if the currently iterated element equals to the element
             * from which we want to exchange
             * and also checks if the current way wasn't used before.
             */
            if ($exchangeWaysArr[$i]['currencyFrom'] == $exchangeFrom && !isset($trackedWays[$i])) {
                /*
                 * Adds on the new steps to result.
                 */
                $currResult = $result;
                $result[] = $exchangeWaysArr[$i];
                /*
                 * Saves already passed steps for backtracking.
                 */
                $currTrackedWays = $trackedWays;
                $trackedWays[$i] = true;
                /*
                 * Recursive call for moving down in a tree.
                 */
                $this->findExchangeWays($exchangeWaysArr, $exchangeWaysArr[$i]['currencyTo'], $exchangeTo, $result, $trackedWays, $totalResults);
                /*
                 * Resets state for next iteration of the loop.
                 */
                $result = $currResult;
                $trackedWays = $currTrackedWays;
            }
        }
    }

    /**
     * @param $totalResults
     * @return array
     * @author Lasha Lomidze <lomidzelf@gmail.com>
     * Since the main search algorithm does not track FULL already used paths,
     * this minor function sanitizes the final array and removes duplicates.
     */
    private function removeDuplicatePaths($totalResults)
    {
        $serialized = array_map('serialize', $totalResults);
        $uniqueResults = array_unique($serialized);
        $uniqueResultsWithKeys = array_intersect_key($totalResults, $uniqueResults);
        return array_values($uniqueResultsWithKeys);
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
