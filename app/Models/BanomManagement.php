<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanomManagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'banom_id',
        'name',
        'position',
        'photo',
        'period',
        'email',
        'phone',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function banom()
    {
        return $this->belongsTo(Banom::class);
    }
}
