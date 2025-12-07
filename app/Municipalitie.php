<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipalitie extends Model
{
    protected $table = "municipalities";
    protected $fillable= ['name_ar','name_en','id_cities']; 

    protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];


    public function affiliates(){
        return $this -> hasMany('App\Affiliate','municipalities_id','id');
    }

    


}
