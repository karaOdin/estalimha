<?php

namespace App\Http\Controllers\Admin\users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\User;
use Hash;

class UserController extends Controller
{
    public $successStatus = 200;
    /** 
     * all user api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $users = User::all();
        $response['users']  =  $users;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update active status api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function update(Request $request){ 
        $user = User::find($request->user_id);
        $user->active = $request->status;
        $user->save();
        $response['user']  =  $user;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * remove user api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function remove(Request $request){ 
        $user = User::find($request->user_id);
        $cart = $user->cart;
        $cartItems = $cart->cartItem;
        foreach($cartItems as $cartItem){
            $cart_options_value = $cartItem->cartOptionValue;
            foreach($cart_options_value as $cart_option_value){
                $cart_option_value->delete();
            }
            $cartItem->delete();
        }
        $cart->delete();
        $user->delete();
        $response['remove']  =  'record has been removed successfully';
        return response()->json($response,  $this-> successStatus); 
    }
}
