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
        'created_by',
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
        'availability_start_time' =>'datetime',
        'availability_end_time' =>'datetime',
        'allowed_domains' =>'array',
        'is_active' =>'boolean',
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
