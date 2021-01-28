<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverOrder extends Model
{
    protected $table = 'drivers_order';
    
    protected $fillable = [
        'driver_id',
        'order_id',
        'latitude',
        'longitude',
        'status',
    ];
    
    public function driver(){
        
        return $this->belongsTo('App\Models\Driver', 'driver_id')->with('driverLocation');
    }
    
    public function order(){
        
        return $this->belongsTo('App\Models\Order', 'order_id')->with('cart')->with('address')->with('user');
    }
    

}
