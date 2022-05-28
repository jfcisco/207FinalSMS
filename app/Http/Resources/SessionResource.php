<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray($request): array
    {
        $startAt = null;
        if ($this->startAt != null) {
            $startAt = $this->parseDate($this->startAt);
        }

        $endAt = null;
        if ($this->endAt != null) {
            $endAt = $this->parseDate($this->endAt);
        }

        $user = User::find($this->clientId);
        $visitor = Visitor::find($this->clientId);

        if ($user != null) {
            return [
                'id' => $this->_id,
                'socket_id' => $this->socketId,
                'clientType' => $this->clientType,
                'user' => new UserResource($visitor),
                'started_at' => $startAt,
                'ended_at' => $endAt,
                'end_reason' => $this->endReason,
            ];
        } else {
            return [
                'id' => $this->_id,
                'socket_id' => $this->socketId,
                'clientType' => $this->clientType,
                'visitor' => new VisitorResource($visitor),
                'started_at' => $startAt,
                'ended_at' => $endAt,
                'end_reason' => $this->endReason,
            ];

        }
    }

    function parseDate($dateFromMongo)
    {
        ob_start();
        var_dump($dateFromMongo);
        $str = ob_get_clean();

        $pieces = explode("string", $str);
        $pieces = explode('"', $pieces[1]);
        $millis = intval($pieces[1]);

        return date('Y-m-d H:i:s', ($millis/1000));
    }
}
