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
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_card_no' => 'required|string', // عدّل حسب نوع الهويه لديك
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = [
            'id_card_no' => $request->input('id_card_no'),
            'password' => $request->input('password'),
        ];
        try {
            // if (! $token = JWTAuth::attempt($credentials)) {
            if (! $token = auth('users-api')->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'بيانات الدخول غير صحيحة'
                ], 401);
            }
            
            
            $user = User::select('id','name','id_card_no','mobile','affiliates_id','status_id')->where('id_card_no', $request->input('id_card_no'))
                                // ->with('client_notes:client_id,note,client_status')
                                ->first();
            if($user->status_id !=2) {
                return response()->json([
                    'message' => 'صاحب الحساب موقوف يرجى الرجوع للإدارة',
                    'status' => false,
                ], 404, [], JSON_UNESCAPED_UNICODE);
            }
            
            $user->token=$token;
            $user->save();

            return response()->json([
                'message' => 'تم',
                'status' => true,
                'data' => $user,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'خطأ في السيرفر أثناء محاولة تسجيل الدخول',
                'error' => $e->getMessage()
            ], 500);
        }

        // إذا نجح
        // return $this->respondWithToken($token);
       
    }
    protected function respondWithToken($token)
    {
        // المدة الافتراضية لانتهاء التوكن (بالدقائق)
        $ttl = auth('users-api')->factory()->getTTL();

        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60, // بالثواني
            'user' => auth('users-api')->user(),
        ]);
    }
    
}