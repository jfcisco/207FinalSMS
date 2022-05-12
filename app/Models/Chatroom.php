<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Chatroom extends Model
{
    use HasFactory;

    public function members() {
        return $this->belongsToMany(User::class, null, 'room_id', 'user_id')->withTimestamps();
    }
}
