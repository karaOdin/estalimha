<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';
    
    protected $fillable = [
        'user_id',
        'gender',
        'date_of_birth_day',
        'city',
        'native',
        'ID_number',
        'ID_card',
        'car_lisense_number',
        'car_paper',
    ];
    
    public function driverOrder(){
        
        return $this->hasOne('App\Models\DriverOrder', 'driver_id');
    }
    
    public function driverLocation(){
        
        return $this->hasOne('App\Models\DriverLocation', 'driver_id');
    }
    
    public function driverOrderPending(){
        
        return $this->hasMany('App\Models\DriverOrderPending');
    }
    
    public function user(){
        
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
