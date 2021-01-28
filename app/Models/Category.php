<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public function categoryRestaurant(){
        
        return $this->hasMany('App\Models\CategoryRestaurant')->with('restaurant');
    }

}
