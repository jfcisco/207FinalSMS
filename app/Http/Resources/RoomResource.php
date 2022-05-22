<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $members = [];
        foreach ($this->members as $memberId) {
            $visitor = Visitor::find($memberId)->first();
            if ($visitor != null) {
                array_push($members, new VisitorResource($visitor));
                continue;
            }

            $user = User::find($memberId)->first();
            if ($user != null) {
                array_push($members, new UserResource($user));
            }
        }
        return [
            'id' => $this->_id,
            'members' => $members,
            'messages' => MessageResource::collection($this->messages),
            'created_at' => $this->created_at,
        ];
    }
}
