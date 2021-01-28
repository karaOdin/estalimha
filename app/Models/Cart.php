<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    
    protected $fillable=['user_id'];

    public function user(){
        
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function cartItem(){
        
        return $this->hasMany('App\Models\CartItem')->with('cartOptionValue')->with('sectionItem');
    }
}
