<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasketsExpense extends Model
{
    protected $table = 'baskets_expenses';

    protected $fillable = [
        'user_id',
        'quantity',
        'returned_quantity',
        'date',
    ];
}