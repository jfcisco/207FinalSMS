<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'chat_widget' => new ChatWidgetResource($this->chat_widget),
            'visitor' => new UserResource($this->visitor),
            'agents' => UserResource::collection($this->agents),
            'socket_id' => $this->socket_id,
            'role' => $this->role,
            'ip_address' => $this->ip_address,
            'browser' => $this->browser,
            'webpage_source' => $this->webpage_source,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
        ];
    }
}
