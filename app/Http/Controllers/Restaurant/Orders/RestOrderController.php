<?php

namespace App\Http\Controllers\Restaurant\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\SectionItem;
use App\Models\Addresses;
use App\Models\DriverOrder;
use App\Models\MenuSection;
use App\Models\CancelEditTiming;
use App\Models\DriverOrderPending;
use App\Models\DriverLocation;
use Hash;
use App\Http\Controllers\API\order\DriverOrderController;

class RestOrderController extends Controller
{
    public $successStatus = 200;
    /** 
     * show order api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function show(Request $request){ 
        $rest_menu = MenuSection::where('menu_id', Auth::user()->restuarant->menu[0]->id)->select('id')->get();
        $carts = Order::select('cart_id')->get();
        $cart_items = CartItem::whereIn('cart_id', $carts)->select('section_item_id')->get();
        $items = SectionItem::whereIn('id', $cart_items)->whereIn('menu_section_id', $rest_menu)->select('id')->get();
        $cart_order_code = CartItem::whereIn('section_item_id', $items)->select('order_code')->get();
        $order = Order::whereIn('order_code', $cart_order_code)->with('cartItem')->with('address')->where('type', 'pickup')->where('status','!=', 'restuarant_finished')
        ->orWhereIn('order_code', $cart_order_code)->with('cartItem')->with('address')->Where('type', 'delivery')->get();
        $response['orders'] = $order;
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
        $order = Order::find($request->item_id);
        $order->status = $request->status;
        $order->save();
        if($request->status === 'Accepted'){
            return redirect()->route('pendingDriverOrders' ,['order_id' => $order->id, 'lat' => $order->lat, 'long' => $order->long]);
        }
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
