<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'roomId',
        'startAt',
        'endAt',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'roomId', 'room_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversationId', 'id');
    }
}
