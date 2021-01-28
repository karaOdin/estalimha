<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverSupport extends Model
{
    protected $table = 'driver_support';

    protected $fillable = [
        'support',
        'text',
    ];

}
