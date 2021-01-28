<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    
    protected $fillable=['sender_id','receiver_id','room_id', 'message'];

    public function room(){
        return $this->belongsTo('App\Models\Room', 'room_id');
    }
}
