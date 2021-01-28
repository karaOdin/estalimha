<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    use HasFactory;

    protected $table = 'item_options';
    
    protected $fillable=['title', 'title_ar', 'section_item_id', 'type', 'required'];

    public function optionValue(){
        
        return $this->hasMany('App\Models\OptionValue');
    }
    public function sectionItem(){
        
        return $this->belongsTo('App\Models\MenuSection', 'section_item_id');
    }
}
