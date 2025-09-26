<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value'
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function getKeyAttribute($value): string
    {
        return $value ?? '';
    }

    public function setKeyAttribute($value): void
    {
        $this->attributes['key'] = $value ?: '';
    }

    public function getValueAttribute($value): string
    {
        return $value ?? '';
    }

    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = $value ?: '';
    }
}