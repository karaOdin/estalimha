<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'email',
        'phone',
        'description',
        'latitude',
        'longitude',
        'delivery_status',
        'delivery_time',
        'delivery_fees',
        'restaurant_code',
        'photo',
        'register_photo',
        'register_number',
        'working_from',
        'working_to',
    ];
    public function categoryRestaurant(){
        
        return $this->hasMany('App\Models\CategoryRestaurant');
    }
    public function menu(){
        
        return $this->hasMany('App\Models\Menu')->with('menuSection');
    }
    public function cuisineRestaurants(){
        
        return $this->hasMany('App\Models\CuisineRestaurant');
    }
    
    public function user(){
        
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
