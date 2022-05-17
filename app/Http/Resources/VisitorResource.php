<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'user' => new UserResource($this->user),
        ];
    }
}
