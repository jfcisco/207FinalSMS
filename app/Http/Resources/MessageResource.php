<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'session' => new SessionResource($this->session),
            'created_by' => new UserResource($this->created_by),
            'attachment_path' => $this->attachment_path,
            'message' => $this->message,
            'is_whisper' => $this->is_whisper,
            'created_at' => $this->created_at,
        ];
    }
}
