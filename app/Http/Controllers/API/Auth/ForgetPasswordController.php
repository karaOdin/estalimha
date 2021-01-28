<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\User;

class ForgetPasswordController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request){ 
        $validator = Validator::make($request->all(), [
            'email'         => 'required', 
        ]);
        do {
            $code = rand(1000,9999);
        } while ( 
            User::where('forget_code',$code)->first()
        );
        $output='<html><body>';
        $output.='<p>Dear client,</p>';
        $output.='<p>Please use the following code <p><strong>'.$code.'</strong></p> to reset your password.</p>';
        $output.='<p>If you did not request this forgotten password email no action';
        $output.='is needed, your password will not be reset. However, you may want to log into';
        $output.='your account and change your security password as someone may have guessed it.</p>';   
        $output.='<p>Thanks,</p>';
        $output.='Estlemha Team';
        $output.='</body></html>';
        $body = $output; 
        $subject = "Password Recovery - Estlemha";
        $user = User::where('email',$request->email)->first();
        $user->forget_code = $code;
        $user->save();
        $to = $request->email;
		$txt = $body;
		$headers = "From: noreply@estlemha.com". "\r\n";
		$headers.= "MIME-Version: 1.0". "\r\n";
        $headers.= "Content-Type: text/html; charset=ISO-8859-1";
		mail($to,$subject,$txt,$headers);
		return response()->json(['success' => 'email has been sent successfull'], $this-> successStatus); 
    }
    public function update(Request $request){
        $user = User::where('email',$request->email)->where('forget_code',$request->code)->get();
        if(count($user) == 0){
            return response()->json(['code' => 'Please write the correct code that has been sent to your email'], 400); 
        }
        $user[0]->password      = bcrypt($request->password);
        $user[0]->forget_code   = Null;
        $user[0]->save();
        return response()->json(['success' => $user], $this-> successStatus); 
    }
}
