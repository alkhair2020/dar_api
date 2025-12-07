<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escort extends Model
{
    protected $table = "escorts";
    protected $fillable= ['name','id_card_no','kinship_id','clients_id']; 

    protected $dates = ['created_at','updated_at'];

    //protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function kinships(){
        return $this -> belongsTo('App\Kinship','kinship_id','id');
    }

    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }
}
