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
}