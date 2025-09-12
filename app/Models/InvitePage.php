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
}