<?php

namespace App\Http\Controllers\Admin\content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Content;
use App\Models\DriverSupport;
use App\Models\DriverOrder;
use App\Models\User;
use App\Models\Fees;
use Hash;

class ContentController extends Controller
{
    public $successStatus = 200;
    /** 
     * all content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $contents       = Content::all();
        $support        = DriverSupport::all();
        $fees           = Fees::find(1);
        if($request->driver_id){
           $user           = User::find($request->driver_id);
           $drivers_order  = DriverOrder::where('driver_id', $user->driver->id)->where('status', 'delivered')->orWhere('status', 'canceled')->with('order')->get();
           $response['rides']      =  $drivers_order;
        }
        $response['support']    =  $support;
        $response['content']    =  $contents;
        $response['fees']       =  $fees;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function update(Request $request){ 
        $content = Content::find(1);
        $content->update($request->all());
        $response['content']  =  $content;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update fees api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function fees(Request $request){ 
        $fees = Fees::find(1);
        $fees->update($request->all());
        $response['fees']  =  $fees;
        return response()->json($response,  $this-> successStatus); 
    }
}
