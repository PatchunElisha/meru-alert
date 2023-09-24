<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethodSearchList extends Model
{
    protected $fillable = [
        'id',
        'shipping_method_id',
    ];
}
