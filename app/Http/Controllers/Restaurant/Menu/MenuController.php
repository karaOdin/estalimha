<?php

namespace App\Http\Controllers\Restaurant\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Menu;
use App\Models\User;
use App\Models\MenuSection;
use App\Models\Restaurant;
use Hash;

class MenuController extends Controller
{
    public $successStatus = 200;
    /** 
     *  show menu api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request){
        $restaurant         = Restaurant::find(Auth::user()->restuarant->id);
        $menu  = new MenuSection;
        $menu->section_name = $request->name;
        $menu->name_ar      = $request->name_ar;
        $menu->menu_id      = $restaurant->menu[0]->id;
        $menu->save();
        $response['menu']    = $menu;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     *  show menu api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show(Request $request){ 
        $menu  = Menu::where('restaurant_id', Auth::user()->restuarant->id)->with('menuSection')->get();
        $response['menu']    =  $menu;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function update(Request $request){
        $item = MenuSection::find($request->item_id);
        $item->update($request->all());
        $response['success']  =  $item;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     *  delete menu api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function delete(Request $request){
        $menu = MenuSection::find($request->user_id);
        $menu_items = $menu->sectionItem;
        foreach($menu_items as $items){
            $items->delete();
        }
        $menu->delete();
        $response['remove']    =  $menu_items; //'record has been removed successfully';
        return response()->json($response,  $this-> successStatus); 
    }
}
