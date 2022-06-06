<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\BelongsToMany;

class ChatWidget extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_by_id',
        'name',
        'color',
        'position',
        'hide_when_offline',
        'hide_when_on_desktop',
        'hide_when_on_mobile',
        'enable_emojis',
        'availability_start_time',
        'availability_end_time',
        'allowed_domains',
        'generated_code',
        'direct_chat_link',
        'is_active',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'hide_when_offline' =>'boolean',
        'hide_when_on_desktop' =>'boolean',
        'hide_when_on_mobile' =>'boolean',
        'enable_emojis' =>'boolean',

        'sched_monday_enabled' => 'boolean',
        'sched_monday_avail_start' => 'datetime',
        'sched_monday_avail_end' => 'datetime',

        'sched_tuesday_enabled' => 'boolean',
        'sched_tuesday_avail_start' => 'datetime',
        'sched_tuesday_avail_end' => 'datetime',

        'sched_wednesday_enabled' => 'boolean',
        'sched_wednesday_avail_start' => 'datetime',
        'sched_wednesday_avail_end' => 'datetime',
            
        'sched_thursday_enabled' => 'boolean',
        'sched_thursday_avail_start' => 'datetime',
        'sched_thursday_avail_end' => 'datetime',
            
        'sched_friday_enabled' => 'boolean',
        'sched_friday_avail_start' => 'datetime',
        'sched_friday_avail_end' => 'datetime',
            
        'sched_saturday_enabled' => 'boolean',
        'sched_saturday_avail_start' => 'datetime',
        'sched_saturday_avail_end' => 'datetime',
            
        'sched_sunday_enabled' => 'boolean',
        'sched_sunday_avail_start' => 'datetime',
        'sched_sunday_avail_end' => 'datetime',
        
        'is_active' =>'boolean',

        'inactivity_timeout_minutes' => 'integer',
    ];

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agents(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
