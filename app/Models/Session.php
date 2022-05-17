<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\BelongsToMany;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chat_widget_id',
        'visitor_id',
        'socket_id',
        'ip_address',
        'browser',
        'webpage_source',
        'started_at',
        'ended_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'started_at' =>'datetime',
        'ended_at' =>'datetime',
    ];

    public function chat_widget(): BelongsTo
    {
        return $this->belongsTo(ChatWidget::class);
    }

    public function agents(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
