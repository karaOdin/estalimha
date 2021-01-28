<?php

namespace App\Http\Controllers\Restaurant\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Menu;
use App\Models\User;
use App\Models\MenuSection;
use App\Models\SectionItem;
use App\Models\ItemOption;
use App\Models\OptionValue;
use Hash;

class ProductsController extends Controller
{
    public $successStatus = 200;
    public $mainpath      = 'attachments';
    /** 
     *  show menu api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request){
        $product    = new SectionItem;
        $product->item_name             = $request->name;
        $product->name_ar               = $request->name_ar;
        $product->item_description      = $request->description;
        $product->description_ar        = $request->description_ar;
        $product->item_price            = $request->item_price;
        $product->menu_section_id       = $request->id;
        $attachmentPath = $this-> mainpath.'/restaurants/'.Auth::user()->restuarant->name.$request->section_name.'_'.$request->id;
        if($request->hasFile('photo')){
            $file = $request->photo;
            $attachmentName = time().$file->getClientOriginalName();	    	
            $file->move($attachmentPath,$attachmentName);
            $product->item_photo ='http://oi-solution.com/EstlemhaBE/public/'.$attachmentPath.'/'.$attachmentName;
        }
        $product->save();
        if($request->options){
            $item_option = new ItemOption;
            $item_option->title             = $request->title;
            $item_option->title_ar          = $request->title_ar;
            $item_option->type              = $request->type;
            $item_option->required          = $request->required;
            $item_option->section_item_id   = $product->id;
            $item_option->save();
            $options_value = $request->option_value;
            $options_value_ar = $request->option_value_ar;
            $options_value_array =  explode(",",$options_value);
            $options_value_ar_array =  explode(",",$options_value_ar);
            foreach($options_value_array as $index => $value){
                $option_value = new OptionValue;
                $option_value->option_name      = $value;
                $option_value->option_name_ar   = $options_value_ar_array[$index];
                $option_value->item_option_id   = $item_option->id;
                $option_value->save();
            }
        }
        $response['product']    =  $product;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     *  show menu api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show(Request $request){
        $menu = Menu::where('restaurant_id', Auth::user()->restuarant->id)->first();
        $menu_section = MenuSection::where('menu_id', $menu->id)->select('id')->get();
        $prodcuts  = SectionItem::whereIn('menu_section_id', $menu_section)->with('menuSection')->get();
        $response['prodcuts']    =  $prodcuts;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     * update content api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function update(Request $request){
        $item = SectionItem::find($request->item_id);
        $item_requested = $request->all();
        $item_requested['item_photo'] = $item->item_photo;
        if($request->hasFile('item_photo')){
            $attachmentPath = $this-> mainpath.'/restaurants/El-Bek_1/'.$item->menuSection->section_name.'_'.$item->menuSection->id;
            $file = $request->item_photo;
            $attachmentName = time().$file->getClientOriginalName();	    	
            $file->move($attachmentPath,$attachmentName);
            $item_requested['item_photo'] ='http://oi-solution.com/EstlemhaBE/public/'.$attachmentPath.'/'.$attachmentName;
        }
        $item->update($item_requested);
        $response['success']  =  $item_requested;
        return response()->json($response,  $this-> successStatus); 
    }
    /** 
     *  delete menu api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function delete(Request $request){
        $product    = SectionItem::find($request->user_id);
        $cart_items  = $product->cartItem;
        foreach($cart_items as $cart_item){
            $order = Order::where('order_code', $cart_item->order_code)->delete();
            $cart_item->delete();
        }
        $product->delete();
        $response['remove']    =  'record has been removed successfully';
        return response()->json($response,  $this-> successStatus); 
    }
}
