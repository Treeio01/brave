<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'title',
        'description',
        'conference_id',
        'worker_tag',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'reference_id')->where('type', 'invite_page');
    }

    public function downloads()
    {
        return $this->hasMany(Download::class, 'reference_id')->where('type', 'invite_page');
    }

    public function getIsActiveAttribute($value): bool
    {
        return (bool) $value;
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getRefAttribute($value): string
    {
        return $value ?? '';
    }

    public function setRefAttribute($value): void
    {
        $this->attributes['ref'] = $value ?: strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
    }

    public function getTitleAttribute($value): string
    {
        return $value ?? 'Untitled Page';
    }

    public function getDescriptionAttribute($value): string
    {
        return $value ?? '';
    }

    public function getWorkerTagAttribute($value): string
    {
        return $value ?? '';
    }
}