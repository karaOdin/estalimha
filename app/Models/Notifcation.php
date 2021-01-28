<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifcation extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    
    protected $fillable=['user_id','data'];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
