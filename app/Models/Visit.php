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
        'user_agent'
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