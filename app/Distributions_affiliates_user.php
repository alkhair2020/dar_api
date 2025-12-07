<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distributions_affiliates_user extends Model
{
    protected $table = "distributions_affiliates_users";
    protected $fillable= ['distributions_id','users_id','clients_id','products_id','status','note']; 

    public function distributions(){
        return $this -> belongsTo('App\Distribution','distributions_id','id');
    } 

    public function users(){
        return $this -> belongsTo('App\User','users_id','id');
    }

    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }

    public function products(){
        return $this -> belongsTo('App\Product','products_id','id');
    }

     public function deliveries(){
        return $this -> hasMany('App\Deliveries','da_users_id','id');
    }   



    protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

}
