<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    protected $fillable = ['name', 'contact_info'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
