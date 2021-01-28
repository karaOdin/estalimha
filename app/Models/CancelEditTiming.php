<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelEditTiming extends Model
{
    protected $table = 'cancel_edit_timing';
    
    protected $fillable = [
        'cancel',
        'edit',
    ];

}
