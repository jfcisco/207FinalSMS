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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'roomId', 'id');
    }
}
