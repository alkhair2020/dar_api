<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsFoodbasketConfig extends Model
{
    protected $fillable = ['product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
