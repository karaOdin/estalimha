<?php

namespace App\Http\Controllers\API\drivers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Driver;
use App\Models\DriverOrder;

class ActiveController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function update(Request $request){
        $dirver_order = DriverOrder::where('driver_id', Auth::user()->driver->id)->where('status', '!=', 'delivered')->first();
        if($dirver_order){
            $response['can not be change'] = 'you can not change your active while haveing an order';
            return response()->json($response, 400); 
        }
        $user = Auth::user();
        $driver = $user->driver;
        $driver->active = $request->active;
        $driver->save();
        $response['driver']  =  $driver;
        
        return response()->json($response, $this-> successStatus); 
    }
}
