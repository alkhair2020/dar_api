<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $table = "recommendations";
    protected $fillable= ['name_ar','name_en','id_card_number','address','note'];

    protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function recommendations(){
        return $this -> hasMany('App\Client','recommendations_id','id'); 
    }

}
