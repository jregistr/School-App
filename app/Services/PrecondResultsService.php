<?php

namespace App\Services;

use App\Util\C;
use Illuminate\Http\Request;

class PrecondResultsService
{

    /**
     * Checks that all parameters exist in request input. Stops at the first missing one if found.
     * @param Request $request - The controller request object.
     * @param array $parameters - The array of parameters to check for.
     * @return array - An array of the form ['success' => 'true', 'name' => 'parameter']
     */
    public function exist($request, $parameters)
    {
        $result = [C::SUCCESS => true, C::NAME => 'none'];

        foreach ($parameters as $p) {
            if ($request->input($p) == null) {
                $result[C::SUCCESS] = false;
                $result[C::NAME] = $p;
                break;
            }
        }

        return $result;
    }


    /**
     * Creates a success json providing data to the user.
     * @param $data - The payload.
     * @return \Illuminate\Http\JsonResponse - Response is of the form {'success' : true, data: {}}
     */
    public function result($data)
    {
        return response()->json(
            [
                C::SUCCESS => true,
                C::DATA => $data
            ]
        );
    }

    /**
     * Creates a failure json informing of a missing parameter.
     * @param $paramName - The name of the missing parameter.
     * @return \Illuminate\Http\JsonResponse - The response to send to the user.
     */
    public function missingParameter($paramName)
    {
        return response()->json(
            [C::SUCCESS => false,
                C::MESSAGE => 'Parameter ' . $paramName . ' is missing']
        );
    }

    /**
     * Creates a failure json message.
     * @param $message - The message for the error.
     * @return \Illuminate\Http\JsonResponse - The response to send to the user.
     */
    public function fail($message)
    {
        return response()->json(
            [
                C::SUCCESS => false,
                C::MESSAGE => $message
            ]
        );
    }

}