<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Damaged_store extends Model
{
    protected $table = "damaged_stores";
    protected $fillable= ['stores_id','warehouses_id','products_id','add_users_id','count','describe_d'];

    // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function stores(){
        return $this -> belongsTo('App\Store','stores_id','id');
    }

    public function warehouses(){
        return $this -> belongsTo('App\Warehouse','warehouses_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

    public function users(){
        return $this -> belongsTo('App\User','add_users_id','id');
    }
}
