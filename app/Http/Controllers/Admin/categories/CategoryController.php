<?php

namespace App\Http\Controllers\Admin\categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Category;
use App\Models\CategoryRestaurant;
use Hash;

class CategoryController extends Controller
{
    public $successStatus = 200;
    /** 
     * all content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $category = Category::all();
        $response['category']  =  $category;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * create category api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request){ 
        $category = new Category;
        $category->name     = $request->name;
        $category->name_ar  = $request->name_ar;
        $category->save();
        $response['category']  =  $category;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * insert category restaurant api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function insert(Request $request){ 
        foreach($request->category_id as $category){
            $category_restaurant = CategoryRestaurant::where('category_id', $category)->where('restaurant_id', $request->restaurant_id)->first();
            if(!$category_restaurant){
                $category_restaurant = new CategoryRestaurant;
                $category_restaurant->category_id     = $category;
                $category_restaurant->restaurant_id   = $request->restaurant_id;
                $category_restaurant->save();
            }
        }
        $response['category_restaurant']  =  $category_restaurant;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function remove(Request $request){ 
        $category = Category::find($request->category_id);
        $category->delete();
        $response['category']  =  $category;
        return response()->json($response,  $this-> successStatus); 
    }
}
