<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Client extends  Authenticatable implements JWTSubject
{
    protected $table = "clients";
    protected $fillable = ['number_of_followers','the_whole_family','id_card_number', 'homeStatus', 'extra_note', 'cam_img', 'nationality_id',
    'name', 'sex', 'phone', 'email', 'date_of_birth', 'family_member', 'separate_family_member', 'marital_status_id',
    'reason_id', 'city_id', 'neighborhood_id', 'address', 'affiliate_id', 'delivery_store_id', 'kind_of_help',
    'basket_due_no', 'certified_by_id', 'note', 'location', 'last_delivery_date', 'next_delivery_date',
    'client_status', 'client_status_note', 'status_temp_perm_id', 'recommendations_by_user_id',
    'recommendations_id', 'user_add_id', 'last_user_updated_id', 'last_delivery_date',
    'amount_of_financialHelp', 'created_at', 'updated_at'];

    // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    protected $dates = ['date_of_birth', 'last_delivery_date', 'next_delivery_date', 'created_at', 'updated_at'];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d'
    ];
    ///////////////////////////////////////////////////
    public function client_notes()
    {
        return $this->hasOne('App\ClientNotes', 'client_id', 'id')->latest('created_at');
    }
    
    public function countaries()
    {
        return $this->belongsTo('App\Countries', 'nationality_id', 'country_code');
    }

    public function usersCertified()
    {
        return $this->belongsTo('App\User', 'certified_by_id', 'id');
    }

    public function recommendations_by_user()
    {
        return $this->belongsTo('App\User', 'recommendations_by_user_id', 'id');
    }

    public function userAddId()
    {
        return $this->belongsTo('App\User', 'user_add_id', 'id');
    }

    public function recommendations()
    {
        return $this->belongsTo('App\Recommendation', 'recommendations_id', 'id');
    }

    public function marital_status()
    {
        return $this->belongsTo('App\Marital_status', 'marital_status_id', 'id');
    }

    public function reason()
    {
        return $this->belongsTo('App\Reason', 'reason_id', 'id');
    }
    public function cities()
    {
        return $this->belongsTo('App\Citie', 'city_id', 'id');
    }

    public function neighborhoods()
    {
        return $this->belongsTo('App\Neighborhood', 'neighborhood_id', 'id');
    }
    public function affiliates()
    {
        return $this->belongsTo('App\Affiliate', 'affiliate_id', 'id');
    }

    public function kind_of_helps()
    {
        return $this->belongsTo('App\Kind_of_help', 'kind_of_help', 'id');
    }

    public function sexs()
    {
        return $this->belongsTo('App\Sex', 'sex', 'id');
    }

    public function status()
    {
        return $this->belongsTo('App\Statu', 'client_status', 'id');
    }

    public function temp_perms()
    {
        return $this->belongsTo('App\temp_perm', 'status_temp_perm_id', 'id');
    }

    public function distributions()
    {
        return $this->hasMany('App\Distribution', 'clients_id', 'id');
    }
    public function clientsdistributions()
    {
        return $this->hasOne(Distribution::class, 'clients_id', 'id')->where('status',1);
    }
    
    public function deliveries()
    {
        return $this->hasMany('App\Deliveries', 'clients_id', 'id');
    }
   

    public function da_user()
    {
        return $this->hasMany('App\Distributions_affiliates_user', 'clients_id', 'id');
    }

    public function shipments()
    {
        return $this->hasMany('App\Shipment', 'clients_id', 'id');
    }

    public function escorts()
    {
        return $this->hasMany('App\Escort', 'clients_id', 'id');
    }

    public function receipt_agents_clients()
    {
        return $this->hasMany('App\Receipt_agents_client', 'clients_id', 'id');
    }
    public function family_members()
    {
        return $this->hasMany('App\Family_member', 'clients_id', 'id');
    }
    
    public function deliveryStore()
    {
        return $this->belongsTo(Store::class, 'delivery_store_id');
    }

    ////////////////////////////////////////////////////////////////////////
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function addDistAvlepClient()
    {
        /*  $userTimezone = Auth::user()->timezone ;
        $lang= LaravelLocalization::getCurrentLocale();
        $firstDateMonth = Carbon::now($userTimezone)->startOfMonth()->format('yy-m-d');

        return $model->select('distributions.*')
        ->orderBy('id', 'desc')
        ->where('date','>',$firstDateMonth)
        ->whereDoesntHave('shipments')
        ->whereDoesntHave('deliveries')
        ->with ('clients:id,name,neighborhood_id','clients.neighborhoods:id,name_'.$lang.'')
        ->with('products:id,name_'.$lang.'')
        ;
        return $this->distributions
        ->orderBy('id', 'desc')
        ->where('status_id', 6)->count()
        ; */
    }
}
