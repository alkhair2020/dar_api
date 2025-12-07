<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dist_store_to_affiliate extends Model
{
    protected $table = "dist_store_to_affiliates";
    protected $fillable= ['stores_id','products_id','affiliates_id','quantity','user_send_id','status','created_at'];

    // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function getStates(){
        return   $this -> status == 1 ? 'لم يتم الاستلام'  : 'تم الاستلام';
      }

    public function stores(){
        return $this -> belongsTo('App\Store','stores_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

    public function affiliates(){
        return $this -> belongsTo('App\Affiliate','affiliates_id','id');
    }

    public function users_send(){
        return $this -> belongsTo('App\User','user_send_id','id');
    }

    /* public function users_delivery(){
        return $this -> belongsTo('App\User','user_delivery_id','id');
    } */

    public function delivery_to_affiliates(){
        return $this -> hasMany('App\Delivery_to_affiliate','dist_s_to_affiliates_id','id');
    }

    ////////////////////////////////////////////////////////////////

    /* public function getStatus(){
        return   $this -> status == 1 ? __('dashboards.pending')  : __('dashboards.approval');
    } */

}
