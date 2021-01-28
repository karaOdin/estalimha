<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'area',
        'street_name',
        'address_type',
        'build_type',
        'house_number',
        'land_mark',
        'phone_number',
        'land_line',
    ];
    
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    public function order(){
        return $this->hasMany('App\Models\Order', 'address_id');
    }

}
