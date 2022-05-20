<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatWidgetResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->_id,
            'created_by' => new UserResource($this->created_by),
            'agents' => UserResource::collection($this->agents),
            'name' => $this->name,
            'color' => $this->color,
            'position' => $this->position,
            'hide_when_offline' => $this->hide_when_offline,
            'hide_when_on_desktop' => $this->hide_when_on_desktop,
            'hide_when_on_mobile' => $this->hide_when_on_mobile,
            'enable_emojis' => $this->enable_emojis,
            'availability_start_time' => $this->availability_start_time,
            'availability_end_time' => $this->availability_end_time,
            'allowed_domains' => $this->allowed_domains,
            'generated_code' => $this->generated_code,
            'direct_chat_link' => $this->direct_chat_link,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
