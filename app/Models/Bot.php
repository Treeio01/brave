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
}