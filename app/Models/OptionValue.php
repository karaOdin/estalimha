<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use HasFactory;

    protected $table = 'option_values';
    
    protected $fillable = ['option_name', 'option_name_ar', 'item_option_id'];
    
    public function itemOption(){
        
        return $this->belongsTo('App\Models\ItemOption', 'item_option_id');
    }
}
