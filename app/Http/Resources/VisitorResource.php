<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'user' => new UserResource($request->user),
            'socket_id' => $this->socket_id,
            'role' => $this->role,
            'ip_address' => $this->ip_address,
            'browser' => $this->browser,
            'webpage_source' => $this->webpage_source,
            'created_at' => $this->created_at,
        ];
    }
}
