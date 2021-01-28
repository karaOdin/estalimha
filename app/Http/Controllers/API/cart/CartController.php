<?php

namespace App\Http\Controllers\API\cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CartOptionValue;
use Hash;

class CartController extends Controller
{
    public $successStatus = 200;
    /** 
     * $categories api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function insert(Request $request){ 
        $order = new Order;
        $cart = Cart::where('user_id', Auth::user()->id)->first();
        if(!$cart){
            $cart = new Cart;
            $cart->user_id = Auth::user()->id;
            $cart->save();
        }
        $old_cart_item = CartItem::where('cart_id', $cart->id)->where('section_item_id', $request->item_id)->where('status', 0)->first();
        if(!$old_cart_item){
            $cart_item = new CartItem;
            $cart_item->cart_id         = $cart->id;
            $cart_item->section_item_id = $request->item_id;
            $cart_item->qantity         = $request->qantity;
            $cart_item->save();
            $selected_option =  $request->selected_option;
            foreach($selected_option as $option_value){
                $cart_item_option_value = new CartOptionValue;
                $cart_item_option_value->option_value_id = $option_value['item_option_id'];
                $cart_item_option_value->cart_item_id = $cart_item->id;
                $cart_item_option_value->save();
            }
        }else if($old_cart_item){
            $old_cart_item->qantity = $request->qantity;
            $old_cart_item->save();
        }
        $response['cart']  =  $cart;
        return response()->json($response); 
    }
}
