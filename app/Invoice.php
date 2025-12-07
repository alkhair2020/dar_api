<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['supplier_id', 'invoice_date', 'order_number', 'invoice_number', 'store_id', 'invoice_value', 'invoice_file'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
