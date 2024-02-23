<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Msg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = Msg::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'msg_type' => $request->msg_type,
            'msg' => $request->msg,
            // 'file_paths' => file needs to be processed and stored here
        ]);

        return response()->json($message);
    }

    public function getMessages(Request $request)
    {
        $messages = Msg::where(function($query) use ($request) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $request->user_id);
        })->orWhere(function($query) use ($request) {
            $query->where('sender_id', $request->user_id)->where('receiver_id', Auth::id());
        })->get();

        return response()->json($messages);
    }
}

