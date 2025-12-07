<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = "shipments";
    protected $fillable= ['distributions_id','products_id','clients_id','quantity','user_delivery_id','status','reason_reject','created_at','updated_at'];

   // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;


    public function distributions(){
        return $this -> belongsTo('App\Distribution','distributions_id','id');
    }

    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

    public function user_delivery(){
        return $this -> belongsTo('App\User','user_delivery_id','id');
    }

    public function deliveries(){
        return $this -> hasMany('App\Deliveries','shipments_id','id');
    }

    /* public function clients(){
        return $this -> hasMany('App\Client','sex','id');
    }

    public function users(){
        return $this -> hasMany('App\User','gender','id');
    }  */
}
