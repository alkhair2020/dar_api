<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Countries extends Model
{
    protected $table = "countries";
    protected $fillable=['country_enName','country_arName','country_enNationality', 'country_arNationality'];
    protected $primaryKey = 'country_code' ;
    protected $appends = ['name' , 'nationality'];

    public $incrementing = false;
    protected $hidden =['created_at','updated_at'];
    public $timestamps = false;

    public function clients(){
        return $this -> hasMany('App\Client','nationality_id','country_code');
    }

    public function users(){
        return $this -> hasMany('App\User','nationality_id','country_code');
    }


    public function getNameAttribute()
    {
        $lang = LaravelLocalization::getCurrentLocale();
        if($lang == 'ar'){
            return $this->country_arName;
        }else{
            return $this->country_enName;

        }

    }
    public function getNationalityAttribute()
    {
        $lang = LaravelLocalization::getCurrentLocale();
        if($lang == 'ar'){
            return $this->country_arNationality;
        }else{
            return $this->country_enNationality;

        }

    }


   // protected $dates = ['date_of_birth','last_delivery_date','next_delivery_date','	created_at','updated_at'];
}
