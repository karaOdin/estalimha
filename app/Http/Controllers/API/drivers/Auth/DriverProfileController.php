<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\User;
use App\Models\Nurse;

class ProfileController extends Controller
{
    public $successStatus = 200;
    /** 
     * profile info api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show(Request $request){ 
        $user = Auth::user();
        $success['user']  =  $user;
        return response()->json(['success' => $success], $this-> successStatus); 
    }
    public function update(Request $request){ 
        $validator = Validator::make($request->all(), [ 
            'user_name'     => 'required',
            'phone'         => 'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);
        }
        if(User::where('phone',$request->phone)->where('id','!=',Auth::user()->id)->first()){
            return response()->json(['error'=>'mobile'], 401);
        }
        $user = Auth::user(); 
        $input = $request->all(); 
        $user->update($input);
        $success['user']  =  $user;
        return response()->json(['success' => $success], $this-> successStatus); 
    }
}
