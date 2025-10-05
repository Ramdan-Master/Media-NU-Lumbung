<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        // Handle boolean values
        if ($setting->type === 'boolean') {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }
        
        return $setting->value;
    }

    public static function set($key, $value, $type = 'text', $description = null)
    {
        // Auto-detect type if not specified
        if ($type === 'text') {
            if (is_bool($value)) {
                $type = 'boolean';
                $value = $value ? '1' : '0';
            } elseif (is_string($value) && strlen($value) > 255) {
                $type = 'textarea';
            }
        }
        
        // Convert boolean to string
        if ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }
        
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }
}
