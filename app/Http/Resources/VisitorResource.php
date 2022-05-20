<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'ip_address' => $this->ipAddress,
            'browser' => $this->browser,
            'created_at' => $this->created_at,
        ];
    }
}
