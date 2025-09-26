<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conference extends Model
{
    use HasFactory;

    protected $fillable = [
        'invite_code',
        'title',
        'description',
        'worker_tag',
        'worker_id',
        'domain',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function invitePages()
    {
        return $this->hasMany(InvitePage::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'reference_id')->where('type', 'conference');
    }

    public function downloads()
    {
        return $this->hasMany(Download::class, 'reference_id')->where('type', 'conference');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function getIsActiveAttribute($value): bool
    {
        return (bool) $value;
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getInviteCodeAttribute($value): string
    {
        return $value ?? '';
    }

    public function setInviteCodeAttribute($value): void
    {
        $this->attributes['invite_code'] = $value ?: strtoupper(substr(md5(uniqid()), 0, 8));
    }

    public function getTitleAttribute($value): string
    {
        return $value ?? 'Untitled Conference';
    }

    public function getDescriptionAttribute($value): string
    {
        return $value ?? '';
    }

    public function getDomainAttribute($value): ?string
    {
        return $value;
    }

    public function getWorkerTagAttribute($value): string
    {
        return $value ?? '';
    }
}