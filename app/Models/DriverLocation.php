<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    protected $table = 'driver_location';
    
    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'status',
    ];
    
    public function driver(){
       return $this->belongsTo('App\Models\Driver', 'driver_id');
    }

}
