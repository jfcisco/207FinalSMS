<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'widgetId',
        'socketId',
        'clientId',
        'clientType',
        'startAt',
        'endAt',
        'endReason',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'startedAt' =>'datetime',
        'endedAt' =>'datetime',
    ];

    public function chat_widget(): BelongsTo
    {
        return $this->belongsTo(ChatWidget::class, 'widgetId', 'session_id');
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class, 'clientId', 'visitor_id');
    }
}
