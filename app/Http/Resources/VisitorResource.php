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
            'webpage_source' => $this->webpage_source,
            'created_at' => $this->created_at,
        ];
    }
}
