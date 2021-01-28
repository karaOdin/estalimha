<?php

namespace App\Http\Controllers\Admin\drivers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Driver;
use App\Models\DriverOrderPending;
use App\Models\DriverOrder;
use App\Models\DriverLocation;
use App\Models\User;
use Hash;

class DriversController extends Controller
{
    public $successStatus = 200;
    /** 
     * all Restaurant api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $users = User::where('role','driver')->with('driver')->get();
        $response['drivers']  =  $users;
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
        $response['drivers']  =  $user;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * remove Restaurant api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function remove(Request $request){ 
        $user = User::find($request->user_id);
        $driver_location        = DriverLocation::where('driver_id', $user->driver->id)->delete();
        $driver_order           = DriverOrder::where('driver_id', $user->driver->id)->delete();
        $driver_location        = DriverOrderPending::where('driver_id', $user->driver->id)->delete();
        $response['remove']     = 'record has been removed successfully';
        return response()->json($response,  $this-> successStatus); 
    }
}
