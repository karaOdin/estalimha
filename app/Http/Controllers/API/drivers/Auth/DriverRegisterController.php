<?php

namespace App\Http\Controllers\API\drivers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\Driver;
use Validator;

class DriverRegisterController extends Controller
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
        $user->role = 'driver';
        $user->user_code  = $code;
        $user->save();
        
        $driver = new Driver;
        $driver->user_id                = $user->id;
        $driver->gender                 = $request->gender;
        $driver->date_of_birth_day      = $request->date_of_birth_day;
        $driver->city                   = $request->city;
        $driver->native                 = $request->native;
        $driver->ID_number              = $request->ID_number;
        $driver->car_lisense_number     = $request->car_lisense_number;
        $attachmentPath = $this-> mainpath.'/car_lisense/'.$user->first_name.'_'.$user->last_name.'_'.$user->id;
        if($request->hasFile('car_paper')){
            $file = $request->car_paper;
            $attachmentName = time().$file->getClientOriginalName();	    	
            $file->move($attachmentPath,$attachmentName);
            $driver->car_paper ='http://oi-solution.com/EstlemhaBE/public/'.$attachmentPath.'/'.$attachmentName;
        }
        $attachmentPath = $this-> mainpath.'/ID_card/'.$user->first_name.'_'.$user->last_name.'_'.$user->id;
        if($request->hasFile('ID_card')){
            $file = $request->ID_card;
            $attachmentName = time().$file->getClientOriginalName();	    	
            $file->move($attachmentPath,$attachmentName);
            $driver->ID_card ='http://oi-solution.com/EstlemhaBE/public/'.$attachmentPath.'/'.$attachmentName;
        }
        $driver->save();
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
