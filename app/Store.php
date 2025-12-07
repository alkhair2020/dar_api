<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = "stores";
    protected $fillable= ['name_ar','name_en','address'];

    protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function add_stores(){
        return $this -> hasMany('App\Add_store','stores_id','id');
    }

    public function damaged_stores(){
        return $this -> hasMany('App\Damaged_store','stores_id','id');
    }

    public function dist_store_to_affiliate(){
        return $this -> hasMany('App\Dist_store_to_affiliate','stores_id','id');
    }

    public function dist_store_to_delivery_users(){
        return $this -> hasMany('App\Dist_store_to_delivery_user','stores_id','id');
    }
    public function product_stock(){
        return $this -> hasMany('App\ProductStock','stores_id','id');
    }
    public function delivered_item(){
        return $this -> hasMany('App\DeliveredItem','stores_id','id');
    }
}
