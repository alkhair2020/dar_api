<?php

namespace App;

use DarkGhostHunter\Larapass\Contracts\WebAuthnAuthenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable  ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','id_card_no','nationality_id','timezone','jobs_id','affiliates_id', 'email', 'password','mobile','gender','birth_date','avatar','status_id', 'nickname',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function countries(){
        return $this -> belongsTo('App\Countries','nationality_id','country_code');
    }

    public function status(){
        return $this -> belongsTo('App\Statu','status_id','id');
    }

    public function getUserRoles()
    {
        return $this->roles->pluck('name');
    }

    /**
     * Get the user's direct permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUserDirectPermissions()
    {
        return $this->permissions()->pluck('name');
    }

    public function sexs(){
        return $this -> belongsTo('App\Sex','gender','id');
    }

    public function job_mohsens(){
        return $this -> belongsTo('App\Job_mohsen','jobs_id','id');
    }

    public function affiliates(){
        return $this -> belongsTo('App\Affiliate','affiliates_id','id');

    }

    ///////////////////////////////////////////////////////////////////////////////

    public function usersCertified(){
        return $this -> hasMany('App\Client','certified_by_id','id');
    }

    public function recommendationsByUser(){
        return $this -> hasMany('App\Client','recommendations_by_user_id','id');
    }

    public function userAddId(){
        return $this -> hasMany('App\Client','user_add_id','id');
    }

    ///////////////////////////////////////////////////////////////////////////

    public function deliveries(){
        return $this -> hasMany('App\Deliveries','delivery_users_id','id');
    }

    public function storage_delivery(){
        return $this -> hasMany('App\Deliveries','delivery_storage_agent_id','id');
    }
    public function baskets_expense(){
        // return $this -> hasMany('App\BasketsExpense','user_id','id');
        return $this->hasOne(BasketsExpense::class, 'user_id', 'id');
    }
    

    public function da_user(){
        return $this -> hasMany('App\Distributions_affiliates_user','users_id','id');
    }

    public function add_stores(){
        return $this -> hasMany('App\Add_store','add_users_id','id');
    }

    public function damaged_stores(){
        return $this -> hasMany('App\Damaged_store','add_users_id','id');
    }

    ///////////////////////////////////////////////////////////////////////
    public function user_accept(){
        return $this -> hasMany('App\Damaged_store','user_accept_id','id');
    }

    public function user_send(){
        return $this -> hasMany('App\Damaged_store','user_send_id','id');
    }

   /*  public function user_delivery(){
        return $this -> hasMany('App\Distribution','users_delivery_id','id');
    } */
    /////////////////////////////////////////////////////////////////////////

    public function dist_store_to_affiliates_uSend(){
        return $this -> hasMany('App\Dist_store_to_affiliate','user_send_id','id');
    }

    public function dist_store_to_affiliates_uDelivery(){
        return $this -> hasMany('App\Dist_store_to_affiliate','user_delivery_id','id');
    }

    ////////////////////////////////////////////////////////////////////////
    public function dist_store_to_delivery_users(){
        return $this -> hasMany('App\Dist_store_to_delivery_user','delivery_users_id','id');
    }

    public function dist_STDU_deliveryEmployee(){
        return $this -> hasMany('App\Dist_store_to_delivery_user','delivery_employee_id','id');
    }

    public function dist_store_to_delivery_users_uSend(){
        return $this -> hasMany('App\Dist_store_to_delivery_user','user_send_id','id');
    }

    public function dist_store_to_delivery_users_uAccept(){
        return $this -> hasMany('App\Dist_store_to_delivery_user','user_accept_id','id');
    }

    public function shipments(){
        return $this -> hasMany('App\Shipment','user_delivery_id','id');
    }

    public function delivery_to_affiliates(){
        return $this -> hasMany('App\Delivery_to_affiliate','delivery_users_id','id');
    }


    
    // JWTSubject methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
