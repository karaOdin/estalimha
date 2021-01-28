<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\User;
use Hash;

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
        $response['user']  =  $user;
        return response()->json($response, $this-> successStatus); 
    }
    public function update(Request $request){ 
        $user = Auth::user(); 
        if($request->email){
                $old_user  = User::where('email', $request->email)->first();
                if(!$old_user &&  Hash::check($request->password, $user->password)){
                    $user->email = $request->email; 
                    $user->save();
                    $response['success'] = 'updated successfully';
                }else if(!Hash::check($request->password, $user->password)){
                    $response['wrong_password'] = 'wrong password';
                }else{
                    $response['already_exist'] = 'this email is already exist';
                }
        }
        if($request->current_password){
            if(Hash::check($request->current_password, $user->password)){
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user->update($input);
                $response['success'] = 'updated successfully';
            }else if(!Hash::check($request->current_password, $user->password)){
                $response['wrong_password'] = 'wrong password';
            }
        }
        if($request->first_name){
            $input = $request->all(); 
            $user->update($input);
            $response['success'] = 'updated successfully';
        }
        $response['user']  =  $user;
        return response()->json($response, $this-> successStatus); 
    }
}
