<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Validator;
use App\Models\User;
use Hash;

class AdminLoginController extends Controller
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
            if(Auth::user()->role !== 'Admin'){
                return response()->json(['error'=>'Unauthorised'], 401); 
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
    public function update(Request $request){ 
        $validator = Validator::make($request->all(), [
            'password'      => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $user = User::where('role', 'Admin')->first();
        if($user->email && Auth::attempt(['email' => $user->email, 'password' => request('password')])){
            $user = Auth::user(); 
            $user->password = bcrypt($request->new_password);
            $user->save();
            $success['user']  =  $user;
            return $success; 
        }else{
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }
}
