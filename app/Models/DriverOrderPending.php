<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverOrderPending extends Model
{
    protected $table = 'drivers_orders_pending';
    
    protected $fillable = [
        'driver_id',
        'order_id',
        'status',
    ];
    
    public function driver(){
       return $this->belongsTo('App\Models\Driver', 'driver_id');
    }
    
    public function order(){
       return $this->belongsTo('App\Models\Order', 'order_id')->with('cart');
    }

}
