<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    
    protected $fillable=['user_id','cart_id','notes','address_id','type', 'payment_method', 'status', 'lat', 'long'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function cart(){
        return $this->belongsTo('App\Models\Cart', 'cart_id')->with('cartItem');
    }
    public function address(){
        return $this->belongsTo('App\Models\Addresses', 'address_id');
    }
    public function driverOrderPending(){
        return $this->hasMany('App\Models\DriverOrderPending', 'order_id');
    }
    public function driverOrder(){
        return $this->hasOne('App\Models\DriverOrder', 'order_id')->with('driver');
    }
    public function room(){
        return $this->hasMany('App\Models\Room', 'order_id');
    }
    public function cartItem(){
        return $this->hasMany('App\Models\CartItem', 'order_code', 'order_code')->with('sectionItem')->with('cartOptionValue');
    }
}
