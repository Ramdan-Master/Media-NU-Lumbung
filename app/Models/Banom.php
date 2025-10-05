<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Banom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'banner_image',
        'website',
        'email',
        'phone',
        'address',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($banom) {
            if (empty($banom->slug)) {
                $banom->slug = Str::slug($banom->name);
            }
        });
    }

    public function management()
    {
        return $this->hasMany(BanomManagement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
