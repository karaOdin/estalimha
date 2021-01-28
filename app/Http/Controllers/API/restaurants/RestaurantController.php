<?php

namespace App\Http\Controllers\API\restaurants;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Fees;
use Hash;

class RestaurantController extends Controller
{
    public $successStatus = 200;
    /** 
     * $restaurants api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(){ 
        $restaurants = Restaurant::with('menu')->with('cuisineRestaurants')->get();
        $Cuisine = Cuisine::all();
        $Fees = Fees::all();
        $response['restaurants'] = $restaurants;
        $response['cuisines'] = $Cuisine;
        $response['fees'] = $Fees;
        return response()->json($response, $this-> successStatus); 
    }
    /** 
     * show rest api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show($id){ 
        $restaurant = Restaurant::find($id)->with('menu')->get();
        return response()->json($restaurant); 
    }
}
