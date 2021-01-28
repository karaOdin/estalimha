<?php

namespace App\Http\Controllers\Restaurant\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Fees;
use App\Models\CuisineRestaurant;
use App\Models\Menu;
use Validator;

class RestRegisterController extends Controller
{
    public $successStatus = 200;
    public $mainpath      = 'attachments';
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'first_name'    => 'required', 
            'last_name'     => 'required', 
            'email'         => 'required|string|email|unique:users,email', 
            'phone'         => 'required|unique:users,phone',
            'password'      => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input); 
        $success['user'] =   $user;
		do {
            $code = rand(1000,9999);
        } while ( 
            User::where('verify_code',$code)->first()
        );
        $user->verify_code = $code;
        $user->role = 'restaurant';
        $user->user_code  = $code;
        $user->save();
        $fees = Fees::find(1);
        $restaurant = new Restaurant;
        $restaurant->user_id            = $user->id;
        $restaurant->name               = $request->restaurant_name;
        $restaurant->name_ar            = $request->name_ar;
        $restaurant->address            = $request->address;
        $restaurant->email              = $user->email;
        $restaurant->phone              = $user->phone;
        $restaurant->description        = $request->description;
        $restaurant->description_ar     = $request->description_ar;
        $restaurant->latitude           = $request->latitude;
        $restaurant->longitude          = $request->longitude;
        $restaurant->delivery_status    = $request->delivery_status;
        $restaurant->register_number    = $request->register_number;
        $restaurant->working_from       = $request->working_from;
        $restaurant->working_to         = $request->working_to;
        $restaurant->delivery_time      = $request->delivery_time;
        $restaurant->restaurant_code    = $code;
        $restaurant->delivery_fees      = $fees->delivery;
        $attachmentPath = $this-> mainpath.'/'.$request->restaurant_name.'_'.$code;
        if($request->hasFile('photo')){
            $file = $request->photo;
            $attachmentName = time().$file->getClientOriginalName();	    	
            $file->move($attachmentPath,$attachmentName);
            $restaurant->photo ='http://oi-solution.com/EstlemhaBE/public/'.$attachmentPath.'/'.$attachmentName;
        }
        if($request->hasFile('register_photo')){
            $file = $request->register_photo;
            $attachmentName = time().$file->getClientOriginalName();	    	
            $file->move($attachmentPath,$attachmentName);
            $restaurant->register_photo ='http://oi-solution.com/EstlemhaBE/public/'.$attachmentPath.'/'.$attachmentName;
        }
        $restaurant->save();
        $cuisines =  $request->cuisine;
        $pieces = explode(",", $cuisines);
        foreach($pieces as $cuisine){
            $cuisine_restaurant = new CuisineRestaurant;
            $cuisine_restaurant->restaurant_id  = $restaurant->id;
            $cuisine_restaurant->cuisine_id     = $cuisine;
            $cuisine_restaurant->save();
        }
        $menu = new Menu;
        $menu->restaurant_id = $restaurant->id;
        $menu->save();
        return response()->json($success, $this-> successStatus); 
    }
}
