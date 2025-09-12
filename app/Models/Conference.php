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
}