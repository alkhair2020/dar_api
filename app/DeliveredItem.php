<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveredItem extends Model
{
    protected $fillable = ['store_id', 'product_id', 'delivered_quantity'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
