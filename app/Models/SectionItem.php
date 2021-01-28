<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionItem extends Model
{
    use HasFactory;

    protected $table = 'section_items';
    
    protected $fillable = [
        'item_name', 'item_description', 'name_ar', 'description_ar', 'item_price', 'item_photo'
    ];
    
    public function itemOption(){
        
        return $this->hasMany('App\Models\ItemOption')->with('optionValue');
    }
    public function menuSection(){
        
        return $this->belongsTo('App\Models\MenuSection', 'menu_section_id')->with('menu');
    }
    public function cartItem(){
        
        return $this->hasMany('App\Models\CartItem');
    }
}
