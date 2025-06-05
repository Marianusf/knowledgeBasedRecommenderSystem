<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'year',
        'price',
        'fuel_type',
        'transmission',
        'vehicle_type',
        'mileage',
        'seats',
        'km_driven',
        'engine_size'
    ];
}
