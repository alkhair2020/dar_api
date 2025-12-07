<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dist_store_to_delivery_user extends Model
{
    protected $table = "dist_store_to_delivery_users";
    protected $fillable= ['stores_id','products_id','delivery_users_id','quantity','user_send_id','user_accept_id','accept_date','status','delivery_employee_id','created_at','updated_at','delivered_at'];

    // protected $hidden =['created_at','updated_at'];
    //public $timestamps = true;

    /* public function getStates(){
        return   $this -> status == 1 ? 'لم يتم الاستلام'  : 'تم الاستلام';
      } */

    public function stores(){
        return $this -> belongsTo('App\Store','stores_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

    public function delivery_users(){
        return $this -> belongsTo('App\User','delivery_users_id','id');
    }

    public function delivery_employee(){
        return $this -> belongsTo('App\User','delivery_employee_id','id');
    }

    public function users_send(){
        return $this -> belongsTo('App\User','user_send_id','id');
    }

    public function users_accept(){
        return $this -> belongsTo('App\User','user_accept_id','id');
    }
}
