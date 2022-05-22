<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'roomId',
        'clientId',
        'clientType',
        'clientName',
        'content',
        'isWhisper',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'isWhisper' =>'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'roomId', 'message_id');
    }
}
