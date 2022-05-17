<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'chat_widgets' => ChatWidgetResource::collection($this->chat_widgets),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
