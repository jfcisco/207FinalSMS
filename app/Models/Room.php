<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'members',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'roomId', 'id');
    }
}
