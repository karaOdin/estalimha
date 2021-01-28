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
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\DriverOrderPending;
use App\Models\DriverLocation;
use App\Models\Notifcation;
use Hash;

class DriverOrderController extends Controller
{
    public $successStatus = 200;
    /** 
     * index api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $user = Auth::user();
        $driver = $user->driver;
        $driver_order = DriverOrder::where('driver_id', $driver->id)->where('status', '!=','delivered')->where('status', '!=',  'canceled')->with('order')->get();
        $response['driver_order'] = $driver_order;
        return response()->json($response);
    }
    /** 
     * show api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show(Request $request){ 
        $user = Auth::user();
        $driver = $user->driver;
        $driver_order_pending = DriverOrderPending::where('driver_id', $driver->id)->where('status', 'pending')->with('order')->get();
        $response['orders_pending'] = $driver_order_pending;
        return response()->json($response);
    }
    /** 
     * insert pending api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function insert(Request $request){ 
        $order_lat  =    $request->lat;
        $order_long =    $request->long;
        $drivers = DriverLocation::all();
        foreach($drivers as $driver){
            $theta  = $order_long - $driver->longitude; 
            $dist   = sin(deg2rad($order_lat)) * sin(deg2rad($driver->latitude)) +  cos(deg2rad($order_lat)) * cos(deg2rad($driver->latitude)) * cos(deg2rad($theta)); 
            $dist   = acos($dist); 
            $dist   = rad2deg($dist); 
            $miles  = $dist * 60 * 1.1515;
            $kilo   = $miles * 1.609344;
            if($kilo < 10 && $driver->status === 'available'){
                $driver_order_pending = new DriverOrderPending;
                $driver_order_pending->driver_id    = $driver->driver_id;
                $driver_order_pending->order_id     = $request->order_id;
                $driver_order_pending->save();
            }
        }
        $response['pend_order'] = $drivers;
        return response()->json($response);
    }
    /** 
     * assign order to driver api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request){ 
        $driver_order_pending = DriverOrderPending::find($request->id);
        $driver_location = DriverLocation::where('driver_id', Auth::user()->driver->id)->first();
        $driver = Driver::find( Auth::user()->driver->id);
        if($driver_order_pending->status === 'pending'){
            if(!$driver->active){
                $response['deactive'] = 'you need to active your account first';
                return response()->json($response, 410);
            }
            $driver_order = new DriverOrder;
            $driver_order->driver_id    = Auth::user()->driver->id;
            $driver_order->order_id     = $driver_order_pending->order_id;
            $driver_order->latitude     = $request->lat;
            $driver_order->longitude    = $request->long;
            $driver_order->save();
            $driver_order_pending->status = 'taken';
            $driver_order_pending->save();
            $driver_location->status = 'busy';
            $driver_location->save();
            $order = Order::find($driver_order_pending->order_id);
            $order->status = 'assigned to driver';
            $order->save();
            $response['driver_order'] = $driver_order;
            return response()->json($response, $this-> successStatus);
        }else if($driver_order_pending->status === 'taken') {
            $response['taken'] = 'order has been already taken';
            return response()->json($response, 410);
        }else{
            return response()->json('some thing went wrong', 404);
        }
    }
    /** 
     * update order driver api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function update(Request $request){
        $driver_order = DriverOrder::where('driver_id', Auth::user()->driver->id)->first();
        $driver_order->status = $request->status;
        if($driver_order->status === 'received order from restaurant'){
            $driver_order->latitude     =    $driver_order->order->lat;
            $driver_order->longitude    =    $driver_order->order->long;
        }
        $driver_order->save();
        $order = Order::find($driver_order->order_id);
        $order->status = $request->status;
        $order->save();
        if($request->status === 'received order from restaurant'){
            $noti = new Notifcation;
            $noti->user_id = $order->user_id;
			$noti->data    =  'your order '.$order->order_code.' in the way to you';
			$noti->save();
			$content = array(
			    "en" => 'your order '.$order->order_code.' in the way to you'
			);
            $fields = array(
    			'app_id' => "91e0f978-01d8-41b1-8738-58d35f748a09",
    			'filters' => array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" =>$order->user_id)),
    			'data' => array("mixice" => "order"),
    			'contents' => $content
    		);
            $fields = json_encode($fields);
        	print("\nJSON sent:\n");
        	print($fields);
    		
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
    												   'Authorization: Basic NWE1YzYzYzItY2JmZS00N2M2LTk0ZDMtOTFmZjI4MGE0ZWVk'));
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($ch, CURLOPT_HEADER, FALSE);
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    		curl_exec($ch);
	    }
	    if($request->status === 'delivered'){
            $noti = Notifcation::where('user_id', $order->user_id)->where('data', 'your order '.$order->order_code.' in the way to you')->delete();
			 $content = array(
			     "en" => 'your order '.$order->order_code.' in the way to you'
			);
            $fields = array(
    			'app_id' => "91e0f978-01d8-41b1-8738-58d35f748a09",
    			'filters' => array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => $order->user_id)),
    			'data' => array("mixice" => "order"),
    			'contents' => $content
    		);
            $fields = json_encode($fields);
        	print("\nJSON sent:\n");
        	print($fields);
    		
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
    												   'Authorization: Basic NWE1YzYzYzItY2JmZS00N2M2LTk0ZDMtOTFmZjI4MGE0ZWVk'));
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($ch, CURLOPT_HEADER, FALSE);
    		curl_setopt($ch, CURLOPT_POST, TRUE);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    		curl_exec($ch);
	    }
        $response['driver_order'] = $driver_order;
        return response()->json($response,  $this-> successStatus);
    }
}
