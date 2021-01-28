<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'user_code',
        'role',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function cart(){
        
        return $this->hasOne('App\Models\Cart', 'user_id')->with('cartItem');
    }
    
    public function address(){
        
        return $this->hasMany('App\Models\Address');
    }
    
    public function order(){
        
        return $this->hasMany('App\Models\Order');
    }
    
    public function driver(){
        
        return $this->hasOne('App\Models\Driver');
    }
    
    public function restuarant(){
        
        return $this->hasOne('App\Models\Restaurant');
    }
    
    public function notification(){
        
        return $this->hasMany('App\Models\Notifcation');
    }

}
