<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray($request)
    {
        $startAt = null;
        if ($this->startAt != null) {
            $startAt = $this->parseDate($this->startAt);
        }

        $endAt = null;
        if ($this->endAt != null) {
            $endAt = $this->parseDate($this->endAt);
        }

        return [
            'id' => $this->_id,
            'messages' => MessageResource::collection($this->messages),
            'startAt' => $startAt,
            'endAt' => $endAt,
            'missed'=> $this->missed,
        ];
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
