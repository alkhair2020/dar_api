<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryPrint extends Model
{
    protected $table = 'delivery_print';
    protected $fillable = ['delivery_id', 'user_id','created_at'];
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
