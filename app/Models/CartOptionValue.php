<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartOptionValue extends Model
{
    use HasFactory;

    protected $table = 'cart_item_option_value';
    
    protected $fillable=['cart_item_id', 'option_value_id'];

    public function cartItem(){
        
        return $this->belongsTo('App\Models\CartItem', 'cart_item_id');
    }
    public function optionValue(){
        
        return $this->belongsTo('App\Models\OptionValue', 'option_value_id');
    }
}
