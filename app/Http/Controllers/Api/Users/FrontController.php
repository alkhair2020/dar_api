<?php
namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;



use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Distribution;
use App\PhoneVerificationLog;
use App\PhoneVerificationCode;

use App\Countries;
use App\Marital_status;
use App\Reason;
use App\Citie;
use App\Neighborhood;
use App\Affiliate;
use App\Store;
use App\Recommendation;
use App\Product;
use App\Kinship;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class FrontController extends Controller
{
    public function lookUps(Request $request)
    {
        $lang = 'ar';

        $countries = Countries::select('country_code', 'country_' . $lang . 'Name as countrayName')->orderBy('countrayName')->limit(5)->get();
        $allMarital_status = Marital_status::select('id', 'name_' . $lang . ' as marital_name')->where('show', 1)->limit(5)->get();
        $reasons = Reason::select('id', 'name_' . $lang . ' as reasons_name')
            ->where('show', 1)->limit(5)
            ->get();
        $cities = Citie::select('id', 'name_' . $lang . ' as cities_name')->where('show', 1)->limit(5)->get();
        $neighborhoods = Neighborhood::select('id', 'name_' . $lang . ' as neighborhoods_name')->where('show', 1)->limit(5)->get();
        $affiliates = Affiliate::select('id', 'name_' . $lang . ' as affiliates_name')->limit(5)->get();
        $stores = Store::select('id', 'name_' . $lang . ' as store_name')->limit(5)->get();
        $users = User::select('id', 'name')->limit(5)->get();
        $recommendations = Recommendation::select('id', 'name_' . $lang . ' as recommendations_name')->limit(5)->get();
        $allProducts = Product::select('id', 'name_' . $lang . ' as products_name')->limit(5)->get();
        $allFamily_relations = Kinship::where('show', 1)->limit(5)->get();
        
        return response()->json([
            'status' => true,
            'data' =>  [
                    'countries' => $countries,
                    'allMarital_status' => $allMarital_status,
                    'reasons' => $reasons,
                    'cities' => $cities,
                    'neighborhoods' => $neighborhoods,
                    'affiliates'   => $affiliates,
                    'stores'   => $stores,
                    'users'=>$users,
                    'recommendations'=>$recommendations,
                    'allProducts'=>$allProducts,
                    'allFamily_relations'=>$allFamily_relations
                ],
        ], 200);
          
    }
}