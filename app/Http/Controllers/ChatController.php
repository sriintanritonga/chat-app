<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;

class ChatController extends Controller

{
    public function index(Request $request)
{
    $users = User::where('id', '!=', auth()->id())->get();

    $selectedUser = User::find($request->user);

    $messages = [];

    if ($selectedUser) {

        $messages = Message::with('user')

            ->where(function($q) use ($selectedUser) {

                $q->where('sender_id', auth()->id())
                  ->where('receiver_id', $selectedUser->id);

            })

            ->orWhere(function($q) use ($selectedUser) {

                $q->where('sender_id', $selectedUser->id)
                  ->where('receiver_id', auth()->id());

            })

            ->get();
    }

    return view('chat', compact(
        'users',
        'messages',
        'selectedUser'
    ));
}
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $message = Message::create([
    'sender_id' => auth()->id(),
    'receiver_id' => $request->receiver_id,
    'message' => $request->message,
]);

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}