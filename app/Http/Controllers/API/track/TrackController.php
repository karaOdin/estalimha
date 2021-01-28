<?php

namespace App\Http\Controllers\API\track;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\DriverLocation;
use App\Models\DriverOrder;
use Hash;

class TrackController extends Controller
{
    public $successStatus = 200;
    /** 
     * all orders api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     public function show($driver_id){ 
        $dirver_location = DriverLocation::where('driver_id', $driver_id)->first();
        $response['location'] = $dirver_location;
        return response()->json($response, $this -> successStatus);
     }
    
}
