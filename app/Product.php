<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $fillable= ['name_ar','name_en','note'];

    protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function distributions(){
        return $this -> hasMany('App\Distribution','products_id','id');
    }

    public function da_user(){
        return $this -> hasMany('App\Distributions_affiliates_user','products_id','id');
    }

    public function deliveries(){
        return $this -> hasMany('App\Deliveries','products_id','id');
    }

    public function stores(){
        return $this -> hasMany('App\Store','products_id','id');
    }

    public function add_stores(){
        return $this -> hasMany('App\Add_store','products_id','id');
    }

    public function damaged_stores(){
        return $this -> hasMany('App\Damaged_store','products_id','id');
    }

    public function dist_store_to_affiliates(){
        return $this -> hasMany('App\Dist_store_to_affiliate','products_id','id');
    }

    public function dist_store_to_delivery_users(){
        return $this -> hasMany('App\Dist_store_to_delivery_user','products_id','id');
    }

    public function shipments(){
        return $this -> hasMany('App\Shipment','products_id','id');
    }

    public function delivery_to_affiliates(){
        return $this -> hasMany('App\Delivery_to_affiliate','products_id','id');
    }

    public function invoiceProducts(){
        return $this->hasMany('App\InvoiceProduct', 'product_id', 'id');
    }

    public function foodbasketConfigs(){
        return $this->hasMany('App\ProductsFoodbasketConfig', 'product_id', 'id');
    }

    /* public function usersCertified(){
        return $this -> belongsTo('App\user','certified_by_id','id');
    } */

    // protected $dates = ['date_of_birth','last_delivery_date','next_delivery_date','	created_at','updated_at'];


    /* public function doctors(){
        return $this -> hasMany('App\Doctor','hospital_id','id');
    } */
}
