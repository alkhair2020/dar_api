<?php
namespace App\Http\Controllers\Api\Clients;

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
        if (!empty($idCardNumber)) {
            
           $client = Client::where('id_card_number', '=', $idCardNumber)
            ->with([
                'marital_status:id,name_ar as maritalStatus',
                'reason:id,name_ar as reasonName',
                'cities:id,name_ar as cities',
                'neighborhoods:id,name_ar as neighborhoods',
                'affiliates:id,name_ar',
                'kind_of_helps:id,name_ar as kindOfHelps',
                'sexs:id,name_ar  as gender',
                'status:id,name_ar as status',
                'recommendations_by_user:id,name',
                'recommendations:id,name_ar as recommendations',
                'affiliates:id,name_ar as affiliateName',
                'receipt_agents_clients:id,name'
            ])->first();
            
                return response()->json([
                    'status' => true,
                    'data' => $client,
                ], 200);
            
        }
    }
}