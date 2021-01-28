<?php

namespace App\Http\Controllers\API\categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Category;
use App\Models\Fees;
use Hash;

class CategoriesController extends Controller
{
    public $successStatus = 200;
    /** 
     * $categories api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(){ 
        $categories = Category::with('categoryRestaurant')->get();
        $Fees = Fees::all();
        $response['fees'] = $Fees;
        $response['categories'] = $categories;
        return response()->json($response); 
    }
}
