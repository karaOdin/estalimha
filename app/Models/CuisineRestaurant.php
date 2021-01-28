<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuisineRestaurant extends Model
{
    use HasFactory;

    protected $table = 'cuisine_restaurants';

    public function cuisine(){
        
        return $this->belongTo('App\Models\Cuisines', 'cuisine_id');
    }
    public function restaurant(){
        
        return $this->belongTo('App\Models\Restaurant', 'restaurant_id');
    }

}
