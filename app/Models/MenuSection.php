<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    use HasFactory;

    protected $table = 'menu_sections';
    
    protected $fillable = [
        'section_name', 'name_ar'
    ];
    
    public function sectionItem(){
        
        return $this->hasMany('App\Models\SectionItem')->with('itemOption');
    }
    public function menu(){
        
        return $this->belongsTo('App\Models\Menu', 'menu_id')->with('restaurant');
    }
}
