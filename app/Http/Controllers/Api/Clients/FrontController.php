<?php
namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PhoneVerificationLog;
use App\PhoneVerificationCode;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use GeniusTS\HijriDate\Hijri;
use App\Services\DeliveryService;
use App\Store;
use Illuminate\Support\Facades\App;
use App\User;
use App\Client;
use App\Distribution;
use App\Deliveries;
use App\ClientNotes;
use App\Receipt_agents_client;
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
            if($client) {
                // Search Data Center 
                $data_center = DB::table('data_center')->select('data_center_status', 'date')->where('client_id', '=', $client->id)->first();
                // Get all distibutions
                $deliveries = Deliveries::where('clients_id', $client->id)
                    ->with('products:id,name_ar as productName')
                    ->limit(5)
                    ->latest()->get();
                // check have distribution
                $distribution = Distribution::where('status', '=', 1)->first();

                // client age
                if($client->date_of_birth){
                    $birth_date = $client->date_of_birth->format('Y-m-d');
                }else{
                    $birth_date = Carbon::now();
                }
                $birthDate = Carbon::createFromDate($birth_date);
                $client->age = $birthDate->diffInYears(Carbon::now());
                
                // client HijriDate date
                $client->clientHijriDate = Hijri::convertToHijri(new \DateTime($client->date_of_birth))->format('Y-m-d');

                // client notes
                $client_notes = ClientNotes::where('client_id', $client->id)->select('user_id', 'note','client_status','created_at')->with('users:id,nickname')->orderBy('id', 'desc')->get();

                // وكلاء عني
                $receiptAgents = Receipt_agents_client::where('clients_id', '=', $client->id)->latest()->get();
               
            }
            // وكيل عنهم
            $agentAsClient = Receipt_agents_client::where('id_card_no', '=', $idCardNumber)->with('clients')->get();
            return response()->json([
                'status' => true,
                'data' =>  [
                    'client' => $client,
                    'data_center' => $data_center,
                    'deliveries' => $deliveries,
                    'distribution' => $distribution,
                     'client_notes' => $client_notes,
                    'receiptAgents'   => $receiptAgents,
                    'agentAsClient'   => $agentAsClient,
                ],
            ], 200);
            
        }
    }
    public function check_cart($date_birth,$family_member,$separate_family,$nationality,$marital_status,$last_delivery_date)
    {

        

            // Calculate the client's age based on their date of birth.
            $now = Carbon::now();
            $client_age = $now->diffInYears(date('Y-m-d', strtotime($date_birth)));

            // Fetch the age condition that matches the client's age.
            $age_id = DB::table('cart_age')->where(function ($query) use ($client_age) {
                $query->where('to_no', '>=', $client_age)
                    ->where('from_no', '<=', $client_age);
            })->first();

            // Fetch the family member condition that matches the total family members.
            $total_family_members = $family_member + $separate_family;
            $client_family = DB::table('cart_family_members')->where(function ($query) use ($total_family_members) {
                $query->where('to_no', '>=', $total_family_members)
                    ->where('from_no', '<=', $total_family_members);
            })->first();

            // Determine the nationality condition.
            $nationality = $nationality == 'SA' ? '1' : '2';

            // Map marital status to a specific condition ID for querying.
            $client_marital_status = $this->mapMaritalStatusToCondition($marital_status);

            // Attempt to fetch a matching cart condition based on the calculated criteria.
            if ($age_id && $client_family) {
                $new_get_cart = DB::table('cart_conditions')
                    ->where(function ($q) use ($nationality) {
                        $q->where('nationality', $nationality)
                            ->orWhere('nationality', 0); // Matches any nationality if necessary.
                    })
                    ->where(function ($q) use ($age_id) {
                        $q->where('age', $age_id->id)
                            ->orWhere('age', 0); // Matches any age if necessary.
                    })
                    ->where(function ($q) use ($client_family) {
                        $q->where('members', $client_family->id)
                            ->orWhere('members', 0); // Matches any number of family members if necessary.
                    })
                    ->where(function ($q) use ($client_marital_status) {
                        $q->where('status', $client_marital_status)
                            ->orWhere('status', 0); // Matches any marital status if necessary.
                    })
                    ->where('active', 1)
                    ->first();

                // Update the basket_due_no property with the result, or default to 1 if no matching condition is found.
                // $this->basket_due_no = $new_get_cart ? $new_get_cart->baskets : 1;
                // $this->numberOfProducts = $new_get_cart ? $new_get_cart->baskets : 1;
                
                if($new_get_cart){
                    
                    $this->canReceiveReceipt($last_delivery_date,$new_get_cart->time);
                }else{
                    $this->basket_time=1;
                }
                
            }
       
    }
    public function canReceiveReceipt($lastReceiptDate,$time)
    {
        // تحويل التواريخ إلى كائنات Carbon
        $lastReceipt = Carbon::parse($lastReceiptDate);

        $newReceipt = Carbon::now();
        

        // التحقق من الفرق بين الأشهر بغض النظر عن اليوم
        $lastReceiptMonth = $lastReceipt->copy()->startOfMonth();
        $newReceiptMonth = $newReceipt->copy()->startOfMonth()->toDateString();

        $monthsDifference = $lastReceiptMonth->diffInMonths($newReceiptMonth);
        if($monthsDifference < $time){
            
            $this->basket_time=0;
        }else{
            $this->basket_time=1;
        }
        // return $monthsDifference >= $time;
    }
    private function mapMaritalStatusToCondition($maritalStatus)
    {
        // Mapping logic based on the marital status.
        switch ($maritalStatus) {
            case 1:
                return 1; // Married
            case 6:
                return 2; // Widowed
            case 7:
                return 3; // Prisoner's family
            case 8:
                return 4; // Citizen's wife
            case 2:
                return 6; // Single
            case 3:
                return 5; // Divorced
            case 4:
                return 9; // Other
            case 5:
                return 11; // Fatherless or Orphan
            default:
                return 11; // Default case if none above matches
        }
    }
    private function initializeDeliveryService(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }
    public function createDelivery(Request $request)
    {
        
        $this->initializeDeliveryService(App::make(DeliveryService::class));
        $client = Client::findOrFail($request->clientId);
        $clientAffiliate = $client->affiliate_id;

        $distribution   = Distribution::find($request->distributions_id);
        // $distStatusID   =   $distribution->status;
        // if($distribution->number_of_products !=$this->numberOfProducts){
        //     $distribution->number_of_products  = $this->numberOfProducts;
        //     $distribution->save();
        // }
       
        if ($distStatusID == 2) {
            return response()->json([
                'status' => false,
                'message' => 'تم تسليم العميل سابقا'
            ], 401);
        } else {
            $this->check_cart($client->date_of_birth,
                            $client->family_member,
                            $client->separate_family_member,
                            $client->nationality_id,
                            $client->marital_status_id,
                            $client->last_delivery_date);
            $date_of_birth_carbon   = new Carbon($this->birth_date);
            $date_of_birth_year     = $date_of_birth_carbon->format('Y');
            $date_of_birth_month    = $date_of_birth_carbon->format('m');
            $date_of_birth_day      = $date_of_birth_carbon->format('d');
            if ($date_of_birth_year > 1700) {
                $d2_carbon = new Carbon($this->birth_date);
                $date_birth = $d2_carbon->format('Y-m-d');
            } else {
                $d2 =  Hijri::convertToHijri(new \DateTime($date_of_birth))->format('Y-m-d');
                $d2_carbon = new Carbon($d2);
                $date_birth = $d2_carbon->format('Y-m-d');
            }
            
            $userTimezone = Auth::user()->timezone;
            if (is_null($this->receipt_agent)) {
                $this->receipt_agent = 0;
            }
            
            $delivery_agent_couunt=count($this->delivery_agent);
            if ( $this->numberOfProducts<1) {
                $this->errorMessage = 'عدد السلال يساوي صفر!';
                return; 
            }else {
                $this->errorMessage = null; 
            }
            
            if(!auth()->user()->can('edit_baskets')){
                if ( $this->numberOfProducts>1) {
                    $this->errorMessage = 'لا يُسمح بتسليم أكثر من سلة.';
                    return; 
                }else {
                    $this->errorMessage = null; 
                }
            }
            

            if (is_null($this->phone)) {
                $this->errorMessage = 'رقم الجوال مطلوب';
                return;
            }
            if (strlen((string)$this->phone) < 12) {
                $this->errorMessage ='رقم الجوال مطلوب ويجب أن يكون 12 رقمًا على الأقل';
                return;
            }
            
            $data = $this->validate();
            $add_eliveries                      = new Deliveries();
            $add_eliveries->clients_id          =$this->clientID;
            $add_eliveries->distributions_id    = $this->distributions_id;
            $add_eliveries->products_id         =$this->productID;
            $add_eliveries->quantity            = $this->numberOfProducts;
            $add_eliveries->delivery_users_id   =Auth::user()->id;
            $add_eliveries->delivery_affiliates_id =Auth::user()->affiliates_id;
            $add_eliveries->affiliates_id       =$clientAffiliate;
            $add_eliveries->delivery_store_id   =$this->delivery_store_id ?? $client->delivery_store_id;
            $add_eliveries->delivery_date       = Carbon::now($userTimezone)->format('Y-m-d H:i:s');
            if ( $delivery_agent_couunt > 0) {
                $add_eliveries->recipient_name      =json_encode($this->delivery_agent,JSON_UNESCAPED_UNICODE);
            }
            $add_eliveries->recipient_agents_clients_id =$this->receipt_agent;
            $add_eliveries->note =$this->distributionNote;
            $add_eliveries->car_number =$this->car_number ?:0;
            $add_eliveries->save();
            
            $data = [
                'phone'                             =>  $this->phone,
                'last_delivery_date'                =>  Carbon::now($userTimezone)->format('Y-m-d H:i:s'),
            ];
            $client->update($data);
            $editedist                            = Distribution::find($this->distributions_id);
            if($client->marital_status_id == 6 || $client->marital_status_id == 7 || $client->marital_status_id == 8){
                if($editedist->number_of_products == $this->numberOfProducts){
                    $editedist->number_of_products = $editedist->number_of_products - $this->numberOfProducts;
                    $editedist->status            = 2;
                }else{
                    $editedist->number_of_products = $editedist->number_of_products - $this->numberOfProducts;
                    $editedist->status            = 1;
                }
            }else{
                $editedist->number_of_products  = $this->numberOfProducts;
                $editedist->status                = 2;
            }
            $editedist->save();
            $this->increment_in_dist_delivery_counter();

            $this->alert('success', 'تم إعتماد التسليم بنجاح 👍 ', [
                'position'  =>  'center',
                'timer'  =>  3000,
                'toast'  =>  false,
                'text'  =>  $this->name,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);
            
        }
    }

}