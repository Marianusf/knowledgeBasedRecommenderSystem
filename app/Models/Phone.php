<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'phone';
    protected $fillable = [
        'company_name',
        'model_name',
        'mobile_weight',
        'ram',
        'front_camera',
        'back_camera',
        'processor',
        'battery_capacity',
        'screen_size',
        'launched_year',
        'price'
    ];
}
