<?php
namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;



use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Distribution;
use App\PhoneVerificationLog;
use App\PhoneVerificationCode;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class FrontController extends Controller
{
    public function searchClient(Request $request)
    {
       
        $idCardNumber = $request->id_card_number;
       
        $validator = Validator::make($request->all(), [
                'id_card_number' => 'required|numeric', // عدّل حسب نوع الهويه لديك
                
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
    
    //    dd($idCardNumber);
        if (!empty($idCardNumber)) {
            

           
            $client = Client::where('id_card_number', '=', $idCardNumber)
           ->first();
            if ($client) {
                return response()->json([
                'status' => true,
                'sgsdghdgf' => $client,
            ], 200);
            }
        }
    }
}