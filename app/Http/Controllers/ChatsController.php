<?php

namespace App\Http\Controllers;

use App\Events\ChatroomCreated;
use App\Models\Chatroom;
use App\Models\Members;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

/*MessageSent event version
-the entire message object gets passed which includes everything including room id
and attachment url
*/

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chats');
    }

    public function fetchMessages()
    {
        // chatrooms and messages in the room
        $chatrooms = $this->fetchChatrooms();

        $roomMessagesMap = array();

        if (count($chatrooms)) {
            foreach ($chatrooms as $chatroom) {
                $messagesFromDb = Message::with('user')->where('room_id', $chatroom['room_id'])->get();

                $messages = array();

                foreach ($messagesFromDb as $messageFromDb) {
                    $messageFromDb['message_id'] = $messageFromDb['_id'];
                    $messages[] = $messageFromDb;
                }

                $roomMessagesMap[] = [
                    'room_id' => $chatroom['room_id'],
                    'room_name' => $chatroom['room_name'],
                    'messages' => $messages,
                ];
            }

            return $roomMessagesMap;
        } else {
            return array();
        }
    }

    public function fetchChatrooms()
    {
        $chatroomsFromDb = User::with('rooms')->find(auth()->user()->id)->rooms;

        $chatrooms = array();

        foreach ($chatroomsFromDb as $chatroomFromDb) {
            $chatroomFromDb['room_id'] = $chatroomFromDb['_id'];

            $chatrooms[] = $chatroomFromDb;
        }

        return $chatrooms;
    }

    public function sendMessage(Request $request)
    {
        // Check if the message has an attachment
        if ($request->has('attachment')) {
            $attachmentPath = $request->file('attachment')->store('images', 'public');

            // Save message with attachment
            $message = auth()->user()->messages()->create([
                'message' => $request->input('message', ''),
                //try to include room_id
                'room_id' => $request->room_id,
                'sent_at' => now(),
                'attachment_path' => asset($attachmentPath)
            ]);

            //use incase room_id isn't getting inserted
            /*
            $message = new Message;
            $message->message = $request->message;
            $message->sender_id = auth()->user()->id;
            $message->room_id = $request->room_id;
            $message->attachment_path => asset($attachmentPath);
            $message->save();
            */


            broadcast(new \App\Events\Message(
                $message->user,
                $message->room_id,
                $message->message,
                $message->attachment_path))->toOthers();

            //broadcast(new MessageSent($message->load('user')))->toOthers();

            return ['status' => 'success', 'imageUrl' => asset($attachmentPath)];
        } else {
            // Save the message under the sending user and active room
            $message = auth()->user()->messages()->create([
                'message' => $request->input('message'),
                'room_id' => $request->room_id,
                'sent_at' => now()
            ]);

            broadcast(new \App\Events\Message(
                $message->user,
                $message->room_id,
                $message->message, null))->toOthers();


            //broadcast(new MessageSent($message->load('user')))->toOthers();
            return ['status' => 'success'];
        }


    }

    public function addRoom(Request $request)
    {
        $newRoom = new Chatroom;
        $newRoom->room_name = $request->room_name;
        $newRoom->save();

        // Add the currently logged in user
        $newRoom->members()->attach(auth()->user()->id);

        // Add the new members to the room, assuming `members` is an array of user IDs
        $newRoom->members()->attach($request->members);
        $newRoom['room_id'] = $newRoom['_id'];

        // Inform others that a chatroom has been created
        broadcast(new ChatroomCreated($newRoom->load('members')))->toOthers();


        return $newRoom;
    }

    public function addMember(Request $request)
    {
        $user = User::where('email', $request->email)->get();

        $members = new Members;
        $members->room_id = $request->room_id;
        $members->user_id = $user[0]->id;
        $members->save();

        $chatroom = Chatroom::where('room_id', $request->room_id)->get();

        $roomMsgs = array();

        if (count($chatroom)) {
            foreach ($chatroom as $result) {
                $messagesFromDb = Message::with('user')->where('room_id', $result->room_id)->get();

                $messages = array();

                foreach ($messagesFromDb as $messageFromDb) {
                    $messageFromDb['message_id'] = $messageFromDb['_id'];
                    $messages[] = $messageFromDb;
                }

                $roomMsgs[] = array('room_id' => $result->room_id, 'room_name' => $result->room_name, 'messages' => $messages);
            }
        }
        broadcast(new \App\Events\Message(
            '',
            $roomMsgs['room_id'],
            auth()->user()->name . ' has added you',
            null,
            $roomMsgs['room_name'],
            $user[0]->id
        ))->toOthers();
    }

    public function test()
    {
        $chatrooms = Chatroom::leftJoin('members', 'members.room_id', '=', 'chatrooms.room_id')
            ->where('members.user_id', auth()->user()->id)
            ->get();

        if (count($chatrooms)) {
            foreach ($chatrooms as $result) {
                $msgs = Message::with('user')->where('room_id', $result->room_id)->max('sent_at');
                $sentTime[] = $msgs;
            }
            arsort($sentTime);
            foreach ($sentTime as $ky => $rm) {
                $roomMsgs[] = $chatrooms[$ky];
            }
            return $roomMsgs;
        } else {
            return array();
        }


        foreach ($roomMsgs as $indx => $room) {
            echo $indx . ' - ' . $room->room_name . '<br>';
        }

        echo '<pre>test';
        var_dump($sentTime);
        //var_dump($roomMsgs);
        echo '</pre>';

    }
}
