<?php

namespace App\Http\Controllers\API\drivers\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\DriverOrder;
use App\Models\DriverOrderPending;
use App\Models\DriverLocation;
use Hash;

class LocationController extends Controller
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
        $driver_order = DriverOrder::where('driver_id', $driver->id)->with('order')->get();
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
        $lat  =    $request->lat;
        $long =    $request->long;
        $driver_location = DriverLocation::where('driver_id', Auth::user()->driver->id)->first();
        if($driver_location){
            $driver_location->latitude  = $lat;
            $driver_location->longitude = $long;
            $driver_location->save();
        }else{
            $driver_location = new DriverLocation;
            $driver_location->latitude  = $lat;
            $driver_location->longitude = $long;
            $driver_location->save();
        }
        $response['location'] = $driver_location;
        return response()->json($response);
    }
}
