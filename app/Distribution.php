<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $table = "distributions";
    protected $fillable= ['distribution_date','clients_id','products_id','number_of_products','status','reason_reject','note','created_at','updated_at'];

    //protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    protected $dates = ['distribution_date','created_at','updated_at'];

    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

    public function  deliveries(){
        return $this ->  hasOne('App\Deliveries','distributions_id');
    }
    public function  alldeliveries(){
        return $this ->hasMany('App\Deliveries','distributions_id')->latest();
    }

    public function da_user(){
        return $this -> hasMany('App\Distributions_affiliates_user','distributions_id','id');
    }

    public function shipments(){
        return $this -> hasMany('App\Shipment','distributions_id','id');
    }

    /* public function users(){
        return $this -> belongsTo('App\User','delivery_user_id','id');
    }  */

     public function scopeDateThisMonts()
    {
        return $this->where('distribution_date', '=','2020-09-17');
    }



}
