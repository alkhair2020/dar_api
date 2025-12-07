<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Add_store extends Model
{
    protected $table = "add_stores";
    protected $fillable= ['stores_id','products_id','add_users_id','count','created_at','updated_at'];

    // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function stores(){
        return $this -> belongsTo('App\Store','stores_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

    public function users(){
        return $this -> belongsTo('App\User','add_users_id','id');
    }

    /* public function clients(){
        return $this -> hasMany('App\Client','client_status','id');
    }  */
}
