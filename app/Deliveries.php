<?php

namespace App;

use App\Traits\FormatsDate;
use App\Traits\HasLocalDates;


use Illuminate\Database\Eloquent\Model;

class Deliveries extends Model
{
    use HasLocalDates;

    protected $table = "deliveries";
    protected $fillable= ['received_note','car_number','clients_id','distributions_id','products_id','quantity','shipments_id','delivery_users_id', 'delivery_flag','delivery_device', 'delivery_storage_date',
    'delivery_affiliates_id','affiliates_id','delivery_date', 'delivery_storage_agent_id' ,'recipient_name','recipient_agents_clients_id','note','d_token','created_at','updated_at','status'];
    protected $casts = [
        'delivery_date' => 'date:Y-m-d H:i',
    ];
    // protected $hidden =['created_at','updated_at'];
    public $timestamps = true;
    //protected $dates = ['delivery_date'];


    protected $dates = ['delivery_date','created_at','updated_at'];

    public function receipt_agents_clients(){
        return $this -> belongsTo('App\Receipt_agents_client','recipient_agents_clients_id','id');
    }

    public function delivery_prints()
    {
        return $this->hasMany(DeliveryPrint::class, 'delivery_id');
    }

    public function clients(){
        return $this -> belongsTo('App\Client','clients_id','id');
    }

    public function delivery_users(){
        return $this -> belongsTo('App\User','delivery_users_id','id');
    }

    public function delivery_storage(){
        return $this -> belongsTo('App\User','delivery_storage_agent_id','id');
    }

    public function  distributions(){
        return $this ->  belongsTo('App\Distribution','distributions_id');
    }

    public function  products(){
        return $this ->  belongsTo('App\Product','products_id');
    }

    public function  da_users(){
        return $this ->  belongsTo('App\Distributions_affiliates_user','da_users_id','id');
    }

    public function  affiliates(){
        return $this ->  belongsTo('App\Affiliate','affiliates_id','id');
    }

    public function  shipments(){
        return $this ->  belongsTo('App\Shipment','shipments_id','id');
    }

    public function clientsrecords()
    {
        return $this->hasMany(ClientAttachments::class,'delivery_id','id');
    }
    // public function delivery_agents()
    // {
    //     return $this->hasOne(DeliveryAgent::class,'delivery_id','id');
    // }

}
