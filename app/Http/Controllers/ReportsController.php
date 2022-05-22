<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Room;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports');
    }

    public function dailyChats(Request $request)
    {
        $startDate = $request->start_date == null ? null : Carbon::parse($request->start_date);
        $endDate = $request->end_date == null ? null : Carbon::parse($request->end_date);

        $dayOfWeek = Carbon::now()->dayOfWeek;
        $dates = $this->generateDates($startDate ?? Carbon::now()->subDays($dayOfWeek - 1), $endDate ?? Carbon::now());

        $messages = Message::orderBy('created_at')->orderBy('created_at', 'DESC')->get()->groupBy(function ($item) {
            return $item->created_at->toDateString();
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

    public function generateDates($startDate, $endDate): Collection
    {
        $dates = collect();
        $startDate = $startDate->copy();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates->put($date->toDateString(), 0);
        }

        return $dates;
    }

    public function todaysHourlyChats()
    {
        $timeNowInHour = Carbon::now()->hour;
        $hours = collect();
        $startTime = Carbon::now()->subHours($timeNowInHour);
        $endTime = Carbon::now();

        for ($date = $startTime; $date->lte($endTime); $date->addHour()) {
            $hours->put($date->format('h A'), 0);
        }

        $messages = Message::orderBy('created_at')->orderBy('created_at', 'DESC')->get()->groupBy(function ($item) {
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

        $answeredChatCount = 0;
        foreach ($rooms as $room) {
            $count = count($room->members);
            if ($count <= 1) {
                $answeredChatCount++;
            }
        }

        return response(['data' => $answeredChatCount], 200);
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
        $timeNowInHour = Carbon::now()->hour;
        $hours = collect();
        $startTime = Carbon::now()->subHours($timeNowInHour);
        $endTime = Carbon::now();

        for ($date = $startTime; $date->lte($endTime); $date->addHour()) {
            $hours->put($date->format('h A'), 0);
        }

        $sessions = Session::orderBy('startAt')->orderBy('startAt', 'DESC')->get()->groupBy(function ($item) {
            return $this->parseTime($item->startAt);
        });

        foreach ($sessions as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $hours = $hours->merge([
                $day => $totalCount
            ]);
        }

        return response(['data' => $hours], 200);
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

    public function dailySessions(Request $request)
    {
        $startDate = $request->start_date == null ? null : Carbon::parse($request->start_date);
        $endDate = $request->end_date == null ? null : Carbon::parse($request->end_date);

        $dayOfWeek = Carbon::now()->dayOfWeek;
        $dates = $this->generateDates($startDate ?? Carbon::now()->subDays($dayOfWeek - 1), $endDate ?? Carbon::now());

        $sessions = Session::orderBy('startAt')->orderBy('startAt', 'DESC')->get()->groupBy(function ($item) {
            return $this->parseDate($item->startAt);
        });

        foreach ($sessions as $key => $message) {
            $day = $key;
            $totalCount = $message->count();
            $dates = $dates->merge([
                $day => $totalCount
            ]);
        }

        return response(['data' => $dates], 200);
    }

    function parseDate($dateFromMongo)
    {
        ob_start();
        var_dump($dateFromMongo);
        $str = ob_get_clean();

        $pieces = explode("string", $str);
        $pieces = explode('"', $pieces[1]);
        $millis = intval($pieces[1]);

        return date('Y-m-d', ($millis / 1000));
    }

    public function todaysLiveSessions()
    {
        $liveSessionsCount = Session::where('endAt', null)->where('startAt', '>=', Carbon::now())->get()->count();
        return response(['data' => $liveSessionsCount], 200);
    }
}


