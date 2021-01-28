<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    
    protected $fillable=['sender_id','receiver_id','order_id', 'type'];

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
    public function messages(){
        return $this->hasMany('App\Models\Message', 'room_id')->orderBy('id', 'ASC');
    }
}
