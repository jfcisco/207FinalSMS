<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'socket_id',
        'ip_address',
        'browser',
        'webpage_source',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
