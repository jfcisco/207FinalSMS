<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'chat_widget_id' => $this->chat_widget_id,
            'chat_widget' => new ChatWidgetResource($this->chat_widget),
            'visitor' => new VisitorResource($this->visitor),
            'agents' => UserResource::collection($this->agents),
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'created_at' => $this->created_at,
        ];
    }
}
