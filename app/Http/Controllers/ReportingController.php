<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Message;
use App\Models\Room;
use App\Models\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function dailyChatsVolume()
    {
        $messages = Message::orderBy('created_at')->orderBy('created_at', 'DESC')->get()->groupBy(function($item) {
            return $item->created_at->format('d/M/Y');
        });

        $chatVolumeMap = array();
        foreach($messages as $key => $messge){
            $day = $key;
            $totalCount = $messge->count();

            $chatVolumeMap[] = [
                $day => $totalCount
            ];
        }
        return response(['data' => $chatVolumeMap], 200);
    }

    public function answeredChatsCount()
    {
        $rooms = Room::all();

        $answeredChatCount = 0;
        foreach($rooms as $room){
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

        $answeredChatCount = 0;
        foreach($rooms as $room){
            $count = count($room->members);
            if ($count == 1) {
                $answeredChatCount++;
            }
        }

        return response(['data' => $answeredChatCount], 200);
    }

    public function todaysLiveSessionsCount()
    {
        $liveSessionsCount  = Session::where('endAt', null)->where('startAt', '>=', Carbon::now())->get()->count();
        return response(['data' => $liveSessionsCount], 200);
    }

    public function todaysHourlyLiveSessionsVolume()
    {
        $liveSessions  = Session::where('endAt', null)->where('startAt', '>=', Carbon::now())->orderBy('startAt', 'DESC')->get()->groupBy(function($item) {
            return $this->parseTime($item->startAt);
        });

        $liveSessionsMap = array();
        foreach($liveSessions as $key => $liveSession){
            $day = $key;
            $totalCount = $liveSession->count();

            $liveSessionsMap[] = [
                $day => $totalCount
            ];
        }
        return response(['data' => $liveSessionsMap], 200);
    }

    public function dailySessionsVolume()
    {
        $sessions  = Session::orderBy('startAt')->orderBy('startAt', 'DESC')->get()->groupBy(function($item) {
            return $this->parseDate($item->startAt);
        });

        $sessionsMap = array();
        foreach($sessions as $key => $session){
            $day = $key;
            $totalCount = $session->count();

            $sessionsMap[] = [
                $day => $totalCount
            ];
        }
        return response(['data' => $sessionsMap], 200);
    }



    function parseDate($dateFromMongo)
    {
        ob_start();
        var_dump($dateFromMongo);
        $str = ob_get_clean();

        $pieces = explode("string", $str);
        $pieces = explode('"', $pieces[1]);
        $millis = intval($pieces[1]);

        return date('d/M/Y', ($millis/1000));
    }

    function parseTime($dateFromMongo)
    {
        ob_start();
        var_dump($dateFromMongo);
        $str = ob_get_clean();

        $pieces = explode("string", $str);
        $pieces = explode('"', $pieces[1]);
        $millis = intval($pieces[1]);

        return date('H', ($millis/1000));
    }
}


