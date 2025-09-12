<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'reference_id',
        'platform',
        'tag',
        'user_agent',
        'wallets',
        'ip_address'
    ];

    protected $casts = [
        'wallets' => 'array',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'reference_id');
    }

    public function invitePage()
    {
        return $this->belongsTo(InvitePage::class, 'reference_id');
    }
}