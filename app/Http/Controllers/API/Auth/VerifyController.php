<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\User;

class VerifyController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function index(Request $request){
        $user = User::where('verify_code',$request->code)->get();
        if(count($user) == 0 ){
            return response()->json(['wrongcode' => 'code is incorrect'], $this-> successStatus);
        }else if($user[0]->active == 1){
            return response()->json(['alreadyused' => 'this code has been already used'], $this-> successStatus);
        }
        $user[0]->active               = true;
        $user[0]->verify_code          = null;
        $user[0]->save();
        $response['user']  =  $user[0];
        Auth::loginUsingId($user[0]->id);
        $response['token'] =  $user[0]->createToken('EstlemhaLogin')-> accessToken;
        return response()->json($response, $this-> successStatus); 
    }
}
