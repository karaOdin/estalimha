<?php

namespace App\Http\Controllers\API\order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Addresses;
use App\Models\DriverOrder;
use App\Models\CancelEditTiming;
use Hash;

class OrderController extends Controller
{
    public $successStatus = 200;
    /** 
     * show order api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function show(Request $request){ 
        $orders = Order::where('user_id', Auth::user()->id)->with('driverOrder')->with('cartItem')->where('status', '!=', 'canceled')->get();
        $orders_code = Order::where('user_id', Auth::user()->id)->where('status', '!=', 'canceled')->select('order_code')->get();
        $timing = CancelEditTiming::find(1);
        $response['orders'] = $orders;
        $response['timing'] = $timing;
        return response()->json($response, $this -> successStatus);
     }
    /** 
     * insert order api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function insert(Request $request){ 
        $order = new Order;
        $order->cart_id         = Auth::user()->cart->id;
        $order->user_id         = Auth::user()->id;
        $order->type            = $request->type;
        $order->lat             = $request->lat;
        $order->long            = $request->long;
        $order->payment_method  = $request->payment_method;
        $order->notes           = $request->notes;
        if(!$request->address_id && $order->type === 'delivery'){
            $address_details    = $request->address;
            $address = new Addresses;
            $address->area              = $address_details['area'];
            $address->street_name       = $address_details['street_name'];
            $address->address_type      = $address_details['area'];
            $address->build_type        = $address_details['build_type'];
            $address->house_number      = $address_details['house_number'];
            $address->land_mark         = $address_details['land_mark'];
            $address->phone_number      = $address_details['phone_number'];
            $address->land_line         = $address_details['land_line'];
            $address->user_id           = Auth::user()->id;
            $address->save();
            $order->address_id         = $address->id;
        }else if ($request->address_id && $order->type === 'delivery'){
            $order->address_id         = $request->id;
        }
        do {
            $code = rand(1000,9999);
        } while ( 
            Order::where('order_code',$code)->first()
        );
        $order->order_code           = $code;
        $order->save();
        $cart_items = CartItem::where('cart_id',Auth::user()->cart->id)->where('status', 0)->get();
        foreach($cart_items as $items){
            $items->status = 1;
            $items->order_code = $code;
            $items->save();
        }
        $response['order'] = $order;
        return response()->json($response);
    }
     /** 
     * update api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function update(Request $request){ 
        $order = Order::find($request->order_id);
        $order->notes = $request->notes;
        $order->save();
        $cart_items = CartItem::where('cart_id',Auth::user()->cart->id)->where('status', 0)->get();
        foreach($cart_items as $items){
            $items->status = 1;
            $items->order_code = $order->order_code;
            $items->save();
        }
        $response['orders'] = $orders;
        return response()->json($response, $this -> successStatus);
     }
     /** 
     * timing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function timing(Request $request){ 
        $order = Order::find($request->order_id);
        if($request->cancel_timeout >= 0){
            $order->cancel_timeout  = $request->cancel_timeout;
        }
        if($request->edit_timeout >= 0){
            $order->edit_timeout    = $request->edit_timeout;
        }
        $order->save();
        $response['order'] = $order;
        return response()->json($response, $this -> successStatus);
     }
    /** 
     * cancel order api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function remove(Request $request){ 
        $order = Order::find($request->order_id);
        $order->status = 'canceled';
        $order->save();
        $driver_order = DriverOrder::where('order_id',$request->order_id)->first();
        $driver_order->status = 'canceled';
        $driver_order->save();
        $response['orders'] = $order;
        return response()->json($response, $this -> successStatus);
     }
}
