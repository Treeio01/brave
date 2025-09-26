<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Worker extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'tag',
        'telegram_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->username ?? $this->name;
    }

    public function getIsActiveAttribute($value): bool
    {
        return (bool) $value;
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getTagAttribute($value): string
    {
        return $value ?? '';
    }

    public function setTagAttribute($value): void
    {
        $this->attributes['tag'] = $value ?: 'worker_' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}