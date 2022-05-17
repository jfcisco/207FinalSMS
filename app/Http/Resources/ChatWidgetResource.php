<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatWidgetResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $request['_id'],
            'created_by' => new UserResource($request->created_by),
            'agents' => UserResource::collection($request->agents),
            'name' => $request->name,
            'color' => $request->color,
            'position' => $request->position,
            'hide_when_offline' => $request->hide_when_offline,
            'hide_when_on_desktop' => $request->hide_when_on_desktop,
            'hide_when_on_mobile' => $request->hide_when_on_mobile,
            'enable_emojis' => $request->enable_emojis,
            'availability_start_time' => $request->availability_start_time,
            'availability_end_time' => $request->availability_end_time,
            'allowed_domains' => $request->allowed_domains,
            'generated_code' => $request->generated_code,
            'direct_chat_link' => $request->direct_chat_link,
            'is_active' => $request->is_active,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'deleted_at' => $request->deleted_at,
        ];
    }
}
