<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'socket_id' => $this->socketId,
            'client_id' => $this->clientId,
            'clientType' => $this->clientType,
            'visitor' => new VisitorResource($this->clientId),
            'started_at' => $this->startedAt,
            'ended_at' => $this->endedAt,
            'created_at' => $this->created_at,
        ];
    }
}
