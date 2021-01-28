<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    
    protected $fillable=['cart_id', 'section_item_id', 'qantity'];

    public function user(){
        
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function cart(){
        
        return $this->belongsTo('App\Models\Cart', 'cart_id');
    }
    public function sectionItem(){
        
        return $this->belongsTo('App\Models\SectionItem', 'section_item_id')->with('menuSection');
    }
    public function cartOptionValue(){
        
        return $this->hasMany('App\Models\CartOptionValue')->with('optionValue');
    }
}
