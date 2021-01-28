<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use Validator;

class RegisterController extends Controller
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
        $user->user_code  = $code;
        $user->save();
		$output='<html><body>';
        $output.='<p>Dear client,</p>';
        $output.='<p>Please use the following code <p><strong>'.$code.'</strong></p> to active your account.</p>';
        $output.='<p>Thanks,</p>';
        $output.='Estlemha Team';
        $output.='</body></html>';
        $body = $output; 
        $subject = "Acount Verification - Estlemha";
        $to =  $request->email;
		$txt = $body;
		$headers = "From: noreply@estlemha.com". "\r\n";
		$headers.= "MIME-Version: 1.0". "\r\n";
        $headers.= "Content-Type: text/html; charset=ISO-8859-1";
		mail($to,$subject,$txt,$headers);
		
        return response()->json($success, $this-> successStatus); 
    }
}
