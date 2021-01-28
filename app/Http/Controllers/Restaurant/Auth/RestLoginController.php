<?php

namespace App\Http\Controllers\Restaurant\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Validator;
use App\Models\User;
use Hash;

class RestLoginController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function index(Request $request){ 
        $validator = Validator::make($request->all(), [
            'password'      => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        if($request->email && Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            if(Auth::user()->role !== 'restaurant'){
                return response()->json(['error'=>'Unauthorised'], 401); 
            }else if(Auth::user()->active !== '1'){
                return response()->json(['error'=>'active'], 401);
            }else{
                $user = Auth::user(); 
                $success['user']  =  $user;
                $success['token'] =  $user->createToken('EstlemhaLogin')-> accessToken;
                return $success; 
            }
        }else{
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }
}
