<?php

namespace App\Http\Controllers\Admin\restaurants;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Restaurant;
use Hash;

class RestaurantsController extends Controller
{
    public $successStatus = 200;
    /** 
     * all Restaurant api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $restaurants = Restaurant::with('user')->get();
        $response['restaurants']  =  $restaurants;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update active status api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function update(Request $request){ 
        $restaurant = Restaurant::find($request->user_id);
        $restaurant->active = $request->status;
        $restaurant->save();
        $user           = $restaurant->user;
        $user->active   = $request->status;
        $user->save();
        $response['restaurant']  =  $restaurant;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * remove Restaurant api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function remove(Request $request){ 
        $restaurant = Restaurant::find($request->user_id);
        $menu = $restaurant->menu;
        $menuSections = $menu[0]->menuSection;
        $categoryRestaurants = $restaurant->categoryRestaurant;
        foreach($categoryRestaurants as $categoryRestaurant){
            $categoryRestaurant->delete();
        }
        foreach($menuSections as $menuSection){
            $sectionItems = $menuSection->sectionItem;
            foreach($sectionItems as $sectionItem){
                $item_options = $sectionItem->itemOption;
                $cart_items = $sectionItem->cartItem;
                foreach($item_options as $item_option){
                    $option_values = $item_option->optionValue;
                    foreach($option_values as $option_value){
                        $option_value->delete();
                    }
                    $item_option->delete();
                }
                foreach($cart_items as $cart_item){
                    $cart_items_value = $cart_item->cartOptionValue;
                    foreach($cart_items_value as $cart_item_value){
                        $cart_item_value->delete();
                    }
                    $cart_item->delete();
                }
                $sectionItem->delete();
            }
            $menuSection->delete();
        }
        $menu[0]->delete();
        $restaurant->delete();
        $response['remove']  = 'record has been removed successfully';
        return response()->json($response,  $this-> successStatus); 
    }
}
