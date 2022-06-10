<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IconResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'path' => $this->path,
        ];
    }
}
