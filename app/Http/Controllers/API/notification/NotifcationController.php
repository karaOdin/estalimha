<?php

namespace App\Http\Controllers\API\notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Notifcation;
use Hash;

class NotifcationController extends Controller
{
    public $successStatus = 200;
    /** 
     * inser notification api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show(Request $request){
        $notifcation = Notifcation::where('user_id', Auth::user()->id)->get();
        $response['notification'] = $notifcation;
        return response()->json($response, $this-> successStatus);
    }
    /** 
     * inser notification api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function insert($user_id, $order_code, $status){ 
	    if($status === 'received order from restaurant'){
            $content = array(
			    "en" => 'your order '.$order_code.' in the way to you'
			);
	    }elseif($status === 'delivered'){
            $content = array(
			    "en" => 'your order '.$order_code.' has been delivered successfully'
			);
	    }elseif($status === 'new_order'){
            $content = array(
			    "en" => 'new order in your waiting list'
			);
	    }
		
		$fields = array(
			'app_id' => "91e0f978-01d8-41b1-8738-58d35f748a09",
			'filters' => array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => $user_id)),
			'data' => array("mixice" => "order"),
			'contents' => $content
		);
		
		$fields = json_encode($fields);
    	print("\nJSON sent:\n");
    	print($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic NWE1YzYzYzItY2JmZS00N2M2LTk0ZDMtOTFmZjI4MGE0ZWVk'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
    }
}
