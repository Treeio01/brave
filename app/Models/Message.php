<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'sender',
        'text'
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function getSenderAttribute($value): string
    {
        return $value ?? 'Unknown';
    }

    public function getTextAttribute($value): string
    {
        return $value ?? '';
    }

    public function setSenderAttribute($value): void
    {
        $this->attributes['sender'] = $value ?: 'Unknown';
    }

    public function setTextAttribute($value): void
    {
        $this->attributes['text'] = $value ?: '';
    }
}