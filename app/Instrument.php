<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    //
    protected $fillable = [
        'client_id',
        'instruments_number'
    ];


}
