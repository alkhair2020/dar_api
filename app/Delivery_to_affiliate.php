<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLocalDates;

class Delivery_to_affiliate extends Model
{
    use HasLocalDates;

    protected $table = "delivery_to_affiliates";
    protected $fillable= ['affiliates_id','dist_s_to_affiliates_id','products_id','quantity','delivery_users_id','update_users_id','name_emp_affiliate','phone_emp_affiliate','id_card_emp_affiliate','note','created_at','updated_at'];

    // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;
    //protected $dates = ['delivery_date'];
     

    protected $dates = ['created_at','updated_at'];



    public function  affiliates(){
        return $this ->  belongsTo('App\Affiliate','affiliates_id','id');
    }

    public function  dist_store_to_affiliates(){
        return $this ->  belongsTo('App\Dist_store_to_affiliate','dist_s_to_affiliates_id','id');
    }

    public function  products(){
        return $this ->  belongsTo('App\Product','products_id','id');
    }

    public function delivery_users(){
        return $this -> belongsTo('App\User','delivery_users_id','id'); 
    }

    
}
