<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        // Save the message to the database
        $message = Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
        ]);

        // Broadcast the message event to the recipient
        broadcast(new MessageSent($message))->toOthers();

        return response(['status' => 'Message sent successfully!']);
    }

    // You could also add a method for retrieving messages if needed
    public function getMessages($userId)
    {
        // Get messages between the authenticated user and the given user
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('from_user_id', auth()->id())
                  ->orWhere('to_user_id', auth()->id());
        })->where(function ($query) use ($userId) {
            $query->where('from_user_id', $userId)
                  ->orWhere('to_user_id', $userId);
        })->get();

        return response()->json($messages);
    }
}
