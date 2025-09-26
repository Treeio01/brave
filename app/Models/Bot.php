<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'name',
        'avatar',
        'mic',
        'hand',
        'avatar_url'
    ];

    protected $casts = [
        'mic' => 'boolean',
        'hand' => 'boolean',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function getMicAttribute($value): bool
    {
        return (bool) $value;
    }

    public function setMicAttribute($value): void
    {
        $this->attributes['mic'] = (bool) $value;
    }

    public function getHandAttribute($value): bool
    {
        return (bool) $value;
    }

    public function setHandAttribute($value): void
    {
        $this->attributes['hand'] = (bool) $value;
    }

    public function getNameAttribute($value): string
    {
        return $value ?? 'Unnamed Bot';
    }

    public function getAvatarAttribute($value): ?string
    {
        return $value;
    }

    public function getAvatarUrlAttribute($value): ?string
    {
        return $value;
    }

    public function setAvatarUrlAttribute($value): void
    {
        $this->attributes['avatar_url'] = $value ?: null;
    }
}