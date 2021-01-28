<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    use HasFactory;

    protected $table = 'cuisines';

    public function cuisineRestaurant(){
        
        return $this->hasMany('App\Models\CuisineRestaurant')->with('restaurant');
    }

}
