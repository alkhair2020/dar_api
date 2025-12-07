<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $table = "affiliates";
    protected $fillable= ['name_ar','name_en','phone','store','total_delivery','note','municipalities_id','logo']; 

    protected $dates = ['created_at','updated_at'];

    //protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function municipalities(){
        return $this -> belongsTo('App\Municipalitie','municipalities_id','id');
    }

    public function clients(){ 
        return $this -> hasMany('App\Client','affiliate_id','id');
    }


    public function users(){
        return $this -> hasMany('App\User','affiliates_id','id');
    } 

    public function deliveries(){
        return $this -> hasMany('App\Deliveries','affiliates_id','id');
    }

    public function dist_store_to_affiliate(){
        return $this -> hasMany('App\Dist_store_to_affiliate','affiliates_id','id');
    }

    public function delivery_to_affiliates(){
        return $this -> hasMany('App\Delivery_to_affiliate','affiliates_id','id');
    }

    // protected $dates = ['date_of_birth','last_delivery_date','next_delivery_date','	created_at','updated_at'];


    /* public function doctors(){
        return $this -> hasMany('App\Doctor','hospital_id','id'); 
    } */
}
