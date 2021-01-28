<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use Validator;

class GoogleController extends Controller
{
    public $successStatus = 200;
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email'         => 'required|string|email', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $user = User::where('email',$request->email)->first();
        if(!$user){
            $user = new User;
            $user->email        = $request->email;
            $user->type         = 'google';
            $user->active       = true;
    		do {
                $code = rand(1000,9999);
            } while ( 
                User::where('verify_code',$code)->first()
            );
            $user->user_code  = $code;
            Auth::login($user);
        }
        $user->first_name   = $request->givenName;
        $user->last_name    = $request->familyName;
        $user->google_id    = $request->google_id;
        $user->save();
        $success['user']    =  $user;
		$success['token']   =  $user->createToken('EstlemhaLogin')-> accessToken;
        return response()->json($success, $this-> successStatus); 
    }
}
