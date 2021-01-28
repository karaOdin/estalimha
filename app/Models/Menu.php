<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    
    protected $fillable = [
        'restaurant_id',
    ];
    public function menuSection(){
        
        return $this->hasMany('App\Models\MenuSection')->with('sectionItem');
    }
    public function restaurant(){
        
        return $this->belongsTo('App\Models\Restaurant', 'restaurant_id');
    }
}
