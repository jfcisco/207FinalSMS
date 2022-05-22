<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'room_id' => $this->roomId,
            'client_id' => $this->clientId,
            'client_type' => $this->clientType,
            'client_name' => $this->clientName,
            'content' => $this->content,
            'is_whisper' => $this->isWhisper,
            'created_at' => $this->created_at,
        ];
    }
}
