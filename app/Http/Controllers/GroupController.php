<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use App\Events\GroupMessageSent;

class GroupController extends Controller
{
    public function show()
    {
        $group = Group::first();

        $messages = GroupMessage::where('group_id', $group->id)
            ->with('user')
            ->latest()
            ->get();

        return view('group-chat', compact('group', 'messages'));
    }

    public function send(Request $request)
    {
        $group = Group::first();

        $message = GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json($message->load('user'));
    }
}