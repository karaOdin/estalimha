<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRestaurant extends Model
{
    use HasFactory;

    protected $table = 'category_restaurants';
    
    protected $fillable=['category_id', 'restaurant_id'];
    
    public function category(){

        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function restaurant(){

        return $this->belongsTo('App\Models\Restaurant', 'restaurant_id');
    }
}
