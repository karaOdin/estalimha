<?php

namespace App\Http\Controllers\Admin\cuisines;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Cuisine;
use Hash;

class CuisinesController extends Controller
{
    public $successStatus = 200;
    /** 
     * all Cuisines api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $cuisines = Cuisine::all();
        $response['cuisines']  =  $cuisines;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * create category api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request){ 
        $cuisine = new Cuisine;
        $cuisine->cuisine_name      = $request->cuisine_name;
        $cuisine->cuisine_name_ar   = $request->cuisine_name_ar;
        $cuisine->save();
        $response['cuisines']  =  $cuisine;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function remove(Request $request){ 
        $cuisine = Cuisine::find($request->user_id);
        $cuisine->delete();
        $response['cuisine']  =  'record has been removed successfully';
        return response()->json($response,  $this-> successStatus); 
    }
}
