<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt_agents_client extends Model
{
    protected $primaryKey = 'id'; 
    protected $table = "receipt_agents_clients";
    protected $fillable= ['name','id_card_no', 'date_of_birth','phone','clients_id','kinship_id'];

    protected $dates = ['created_at','updated_at'];

    //protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function kinships(){
        return $this -> belongsTo('App\Kinship','kinship_id','id');
    }

    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }

    public function deliveries(){
        return $this -> hasMany('App\Deliveries','recipient_agents_clients_id','id');
    }
}
