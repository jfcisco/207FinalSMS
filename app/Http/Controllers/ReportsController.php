<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Message;
use App\Models\Room;
use App\Models\Session;
use App\Models\Visitor;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports');
    }

    public function live()
    {
        return view('livereports');
    }

    public function dailyChats(Request $request)
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $startDate = $request->start_date == null ? Carbon::today()->subDays($dayOfWeek) : Carbon::parse($request->start_date);
        $endDate = $request->end_date == null ? Carbon::today() : Carbon::parse($request->end_date);

        $dates = $this->generateDates($startDate, $endDate);

        $messages = Message::where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate->addDay())
            ->orderBy('created_at', 'DESC')->get()->groupBy(function ($item) {
            return $item->created_at->format('d/M/Y');
        });

        foreach ($messages as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $dates = $dates->merge([
                $day => $totalCount
            ]);
        }

        return response(['data' => $dates], 200);
    }

    public function todaysHourlyChats()
    {
        $hours = collect();

        $startTimeIterator = Carbon::today();
        $endTimeIterator = Carbon::now();

        for ($date = $startTimeIterator; $date->lte($endTimeIterator); $date->addHour()) {
            $hours->put($date->format('h A'), 0);
        }

        $messages = Message::where('created_at', '>=', Carbon::today())->orderBy('created_at')->get()->groupBy(function ($item) {
            return $item->created_at->format('h A');
        });

        foreach ($messages as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $hours = $hours->merge([
                $day => $totalCount
            ]);
        }

        return response(['data' => $hours], 200);
    }

    public function todaysAnsweredChatsCount()
    {
        $rooms = Room::all();

        $answeredChatCount = 0;
        foreach ($rooms as $room) {
            $isTodaysMessage = Carbon::parse($room->messages->first()->created_at)->isToday();
            if ($isTodaysMessage) {
                $count = count($room->members);
                if ($count > 1) {
                    $answeredChatCount++;
                }
            }
        }

        return response(['data' => $answeredChatCount], 200);
    }

    public function answeredChatsCount()
    {
        $rooms = Room::all();

        $answeredChatCount = 0;
        foreach ($rooms as $room) {
            $count = count($room->members);
            if ($count > 1) {
                $answeredChatCount++;
            }
        }

        return response(['data' => $answeredChatCount], 200);
    }

    public function missedChatsCount()
    {
        $rooms = Room::all();
        $emptyRooms = array();
        foreach($rooms as $room){
            $count = count($room->members);
            if($count <= 1){
                $emptyRooms[] =  $room->_id;
            }
        }
        $conversations = Conversation::all();

        $missed = 0;
        foreach ($conversations as $convo) {
            if($convo->endAt == !null){
                if(in_array($convo->roomId, $emptyRooms)){
                    $missed++;
                }
            }
        }

        return response(['data' => $missed], 200);
    }

    public function todaysMissedChatsCount()
    {
        $rooms = Room::all();

        $answeredChatCount = 0;
        foreach ($rooms as $room) {
            $isTodaysMessage = Carbon::parse($room->messages->first()->created_at)->isToday();
            if ($isTodaysMessage) {
                $count = count($room->members);
                if ($count <= 1) {
                    $answeredChatCount++;
                }
            }
        }

        return response(['data' => $answeredChatCount], 200);
    }

    public function todaysHourlySessions()
    {
        $hours = collect();

        $startTimeIterator = Carbon::today();
        $endTimeIterator = Carbon::now();

        for ($date = $startTimeIterator; $date->lte($endTimeIterator); $date->addHour()) {
            $hours->put($date->format('h A'), 0);
        }

        $conversations = Conversation::where('startAt', '>=', Carbon::today())
            ->orderBy('startAt')->get()->groupBy(function ($item) {
            return $this->parseTime($item->startAt);
        });

        foreach ($conversations as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $hours = $hours->merge([
                $day => $totalCount
            ]);
        }

        return response(['data' => $hours], 200);
    }

    public function dailySessions(Request $request)
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $startDate = $request->start_date == null ? Carbon::today()->subDays($dayOfWeek) : Carbon::parse($request->start_date);
        $endDate = $request->end_date == null ? Carbon::today() : Carbon::parse($request->end_date);

        $dates = $this->generateDates($startDate, $endDate);

        $conversations = Conversation::where('startAt', '>=', $startDate)
            ->where('startAt', '<', $endDate->addDay())
            ->orderBy('startAt', 'DESC')->get()->groupBy(function ($item) {
                return $this->parseDate($item->startAt);
            });

        foreach ($conversations as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $dates = $dates->merge([
                $day => $totalCount
            ]);
        }

        return response(['data' => $dates], 200);
    }

    public function todaysLiveSessions()
    {
        $liveSessionsCount = Session::where('endAt', null)->where('startAt', '>=', Carbon::today())->get()->count();
        return response(['data' => $liveSessionsCount], 200);
    }

    public function generateDates($startDate, $endDate): Collection
    {
        $dates = collect();
        $startDate = $startDate->copy();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates->put($date->format('d/M/Y'), 0);
        }

        return $dates;
    }

    function parseDate($dateFromMongo)
    {
        ob_start();
        var_dump($dateFromMongo);
        $str = ob_get_clean();

        $pieces = explode("string", $str);
        $pieces = explode('"', $pieces[1]);
        $millis = intval($pieces[1]);

        return date('d/M/Y', ($millis / 1000));
    }

    function parseTime($dateFromMongo)
    {
        ob_start();
        var_dump($dateFromMongo);
        $str = ob_get_clean();

        $pieces = explode("string", $str);
        $pieces = explode('"', $pieces[1]);
        $millis = intval($pieces[1]);

        return date('h A', ($millis / 1000));
    }
    public function liveVisitorSessions()
    {
        $liveVisitorSessions = Session::where('endAt', null)->where('clientType', "visitor")->get();
        $output = array();
        foreach($liveVisitorSessions as $vSession){
            $visitors = Visitor::where("_id", $vSession->clientId)->get();
            //echo $vSession->startAt->toDateTime()->format('U.u')."<br>";
            foreach($visitors as $visitor){
                $room = Room::where('members.clientId',$vSession->clientId)->first();
                $conversation = Conversation:: where('roomId',$room->_id)->where('endAt', null)->get();
                if($conversation->isNotEmpty()){
                    $output[]=array(
                        "socketId" => $vSession->socketId,
                        "ipAddress" => $visitor->ipAddress,
                        "browser" => $visitor->browser,
                        "roomId" => $room->_id,
                        "fromURL" => $visitor->webpage_source,
                        "startAt" => $vSession->startAt->toDateTime()->format(DATE_ISO8601),
                        "time" => "",
                        "pageTitle" => $vSession->pageTitle,
                        "fullUrl" => $vSession->fullUrl,
                    );
                }else{
                    $vSession->endAt = now();
                    $vSession->save();
                }
            }
        }
        return response(['data' => $output], 200);
    }

    public function pastConversations(Request  $request)
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $startDate = $request->start_date == null ? Carbon::today()->subDays($dayOfWeek) : Carbon::parse($request->start_date);
        $endDate = $request->end_date == null ? Carbon::today() : Carbon::parse($request->end_date);

        $conversations = Conversation::where('endAt', '!=', null)
        ->where('startAt', '>=', $startDate)
            ->where('startAt', '<', $endDate->addDay())
            ->orderBy('startAt', 'DESC')->get();


        return response(['data' => ConversationResource::collection($conversations)], 200);
    }
}


