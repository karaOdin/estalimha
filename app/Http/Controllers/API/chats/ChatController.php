<?php

namespace App\Http\Controllers\API\chats;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use App\Models\Room;
use App\Models\Message;
use App\Models\Order;
use Hash;

class ChatController extends Controller
{
    public $successStatus = 200;
    /** 
     * create room api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function create(Request $request){ 
        $room = Room::where('sender_id', Auth::user()->id)->where('receiver_id', $request->receiver_id)->where('type',$request->type)->orWhere('sender_id', $request->receiver_id)->where('receiver_id', Auth::user()->id)->where('type',$request->type)->first();
        if(!$room){
            $room = new Room;
            $room->sender_id     = Auth::user()->id;
            $room->receiver_id   = $request->receiver_id;
            $room->order_id      = $request->order_id;
            $room->type          = $request->type;
            $room->save();
        }
        $response['room']  =  $room;
        return response()->json($response); 
    }
    /** 
     * insert msge api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function insert(Request $request){ 
        $room = Room::find($request->room_id);
        $message = new Message;
        $message->room_id       = $request->room_id;
        $message->sender_id     = Auth::user()->id;
        if(Auth::user()->id == $room->sender_id){
            $message->receiver_id   = $room->receiver_id;
        }else{
            $message->receiver_id   = $room->sender_id;
        }
        $message->message       = $request->message;
        $message->save();
        $response['message']  =  $message;
        return response()->json($response); 
    }
    /** 
     * show room msgs api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function show($room_id){ 
        $room = Room::where('id', $room_id)->with('messages')->first();
        $response['room']  =  $room;
        return response()->json($response, $this-> successStatus); 
    }
}
