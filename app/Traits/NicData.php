<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait NicData
{
    /**
     * Main function to call API with provided data.
     */
    public function callApi($data)
    {
        // Check for presence of required data
        if (empty($data['id_no'])) {
            return response()->json(['error' => "رقم الهوية مطلوب", 'validation' => true], 400);
        }

        if (empty($data['birth_month']) || empty($data['birth_year'])) {
            return response()->json(['error' => "تاريخ الميلاد مطلوب", 'validation' => true], 400);
        }

        $token = $this->getToken($data['user_id']);
        if (!$token) {
            return response()->json(['error' => 'Failed to authenticate with the API.'], 500);
        }

        $response = $this->sendApiRequest(
            $data['national'],
            $data['id_no'],
            date('Y-m', strtotime($data['birth_year'] . '-' . $data['birth_month'])),
            $token
        );

        return $this->processApiResponse($response, $data['id_no']);
    }

    /**
     * Retrieves or refreshes the API token as necessary.
     */
    private function getToken($userId)
    {
        // check for login date in DB, if less than 24 h, get token, if not new login
        $logged = DB::table('user_datacenter_log')->where('user_id', $userId)->orderByDesc('login_date')->first();

        if ($logged && now()->diffInHours($logged->login_date) < 24) {
            return $logged->token;
        }

        return $this->refreshToken($userId);
    }

    /**
     * Refreshes the API token by performing a login request.
     */
    private function refreshToken($userId)
    {
        $response = Http::withHeaders([
            'app-id' => env('API_APP_ID'),
            'app-key' => env('API_APP_KEY'),
        ])->get(env('API_LOGIN_URL'), [
            'Username' => env('API_USERNAME'),
            'Password' => env('API_PASSWORD'),
        ]);

        if ($response->successful()) {
            $token = $response['token_type'] . ' ' . $response['access_token'];
            DB::table('user_datacenter_log')->insert([
                'user_id' => $userId,
                'token' => $token,
                'login_date' => now(),
            ]);

            return $token;
        }

        Log::error('Failed to refresh API token.', ['user_id' => $userId]);
        return null;
    }

    /**
     * Sends a request to the API.
     */
    private function sendApiRequest($national, $idNo, $birthDate, $token)
    {
        $service_identifier = $national == 1 ? env('API_SERVICE_IDENTIFIER_GET_SA') : env('API_SERVICE_IDENTIFIER_GET_NON_SA');

        $queryArray = $national ==1 ? 
            [
                "nin" => $idNo,
                "dateString" =>  $birthDate
            ]:[
                "iqama" => $idNo,
                "birthDateG" =>  $birthDate
            ];

        $response = Http::withHeaders([
            'Authorization' => $token,
            'app-id' => env('API_APP_ID'),
            'app-key' => env('API_APP_KEY'),
            'service-identifier' => $service_identifier,
            'usage-code' => env('API_USAGE_CODE'),
            'operator-id' => env('API_OPERATOR_ID'),
        ])->get(env('API_DATA_ENDPOINT'), $queryArray);

        return $response;
    }

    /**
     * Processes the API response.
     */
    private function processApiResponse($response, $idNo)
    {

        if ($response->successful()) {
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } else {
            Log::error('API request failed.', ['id_no' => $idNo, 'error' => $response->body()]);
            $errorMessage = $this->determineErrorMessage($response);
            return response()->json(['error' => $errorMessage, 'validation' => false], $response->status());
        }
    }

    /**
     * Determines an appropriate error message based on the API response.
     */
    // private function determineErrorMessage($response)
    // {
    //     $data = $response->json();
    //     if (isset($data['error'])) {
    //         return $data['error']['message'];
    //     }
    //     return 'An unknown error occurred.';
    // }

    private function determineErrorMessage($response)
    {
        $data = $response->json();

        if (isset($data['error'])) {
            $errorCode = $data['error']['errorDetail']['errorCode'];
            switch ($errorCode) {
                case 'AUTH-001':
                    return 'Authentication failed.';
                case 'AUTH-002':
                    return 'Invalid app key.';
                case 'AUTH-003':
                    return 'Invalid app id.';
                case 'AUTH-004':
                    return 'Invalid Access Token.';
                case 'AUTH-005':
                    return 'Expired access token.';
                case 'AUTH-006':
                    return 'Invalid client id.';
                case 'AUTH-007':
                    return 'This account is disabled.';
                case 'AUTH-008':
                    return 'Missing required header.';
                case 'AUTH-009':
                    return 'Unsupported header.';
                case 'AUTH-010':
                    return 'Invalid header value.';
                case 'AUTH-011':
                    return 'Condition headers are not supported.';
                case 'PERM-001':
                    return 'Client access denied';
                case 'PERM-002':
                    return 'User access denied.';
                case 'PERM-003':
                    return 'The API method is not allowed.';
                case 'PERM-004':
                    return 'Insufficient account permissions.';
                case 'PERM-005':
                    return 'Write operations are not allowed.';
                case 'PERM-006':
                    return 'Read operations are currently disabled.';
                case 'RQST-011':
                    return 'Invalid Input.';
                case 'RQST-012':
                    return 'Missing Input.';
                case 'RQST-014':
                    return 'Client not registered on this Query.';
                case 'RQST-015':
                    return 'Birth Date is not matching';
                case 'RQST-016':
                    return 'Usage Code not registered on this plan.';
                case 'RQST-101':
                    return 'Invalid main input.';
                case 'RQST-102':
                    return 'Invalid cross-check format.';
                case 'RQST-103':
                    return 'Missing cross-check.';
                case 'SERV-001':
                    return 'Service not found.';
                case 'SERV-002':
                    return 'Operation timed out.';
                case 'SERV-003':
                    return 'Internal error.';
                case 'SERV-004':
                    return 'Service Unavailable.';
                case 'SERV-001': // Note: Duplicate error code, might be a typo in the source.
                    return 'Remote service is down.';
                default:
                    return 'Unknown error.';
            }
        }

        return 'An unknown error occurred.';
    }

}
