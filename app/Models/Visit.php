<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'reference_id',
        'ip_address',
        'user_agent',
        'country',
        'country_code',
        'flag'
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'reference_id');
    }

    public function invitePage()
    {
        return $this->belongsTo(InvitePage::class, 'reference_id');
    }

    public function getTypeAttribute($value): string
    {
        return $value ?? 'unknown';
    }

    public function setTypeAttribute($value): void
    {
        $this->attributes['type'] = $value ?: 'unknown';
    }

    public function getReferenceIdAttribute($value): string
    {
        return $value ?? '';
    }

    public function setReferenceIdAttribute($value): void
    {
        $this->attributes['reference_id'] = $value ?: '';
    }

    public function getIpAddressAttribute($value): string
    {
        return $value ?? '0.0.0.0';
    }

    public function setIpAddressAttribute($value): void
    {
        $this->attributes['ip_address'] = $value ?: '0.0.0.0';
    }

    public function getUserAgentAttribute($value): string
    {
        return $value ?? '';
    }

    public function setUserAgentAttribute($value): void
    {
        $this->attributes['user_agent'] = $value ?: '';
    }

    public function getCountryAttribute($value): string
    {
        return $value ?? 'Unknown';
    }

    public function setCountryAttribute($value): void
    {
        $this->attributes['country'] = $value ?: 'Unknown';
    }

    public function getCountryCodeAttribute($value): string
    {
        return $value ?? 'XX';
    }

    public function setCountryCodeAttribute($value): void
    {
        $this->attributes['country_code'] = $value ?: 'XX';
    }

    public function getFlagAttribute($value): string
    {
        return $value ?? '🌍';
    }

    public function setFlagAttribute($value): void
    {
        $this->attributes['flag'] = $value ?: '🌍';
    }
}