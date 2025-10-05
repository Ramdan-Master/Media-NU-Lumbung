<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category_id',
        'area_id',
        'status',
        'is_featured',
        'is_trending',
        'featured_areas',
        'trending_areas',
        'view_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'featured_areas' => 'array',
        'trending_areas' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if (empty($news->excerpt)) {
                $news->excerpt = Str::limit(strip_tags($news->content), 150);
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query, $areaId = null)
    {
        if ($areaId) {
            return $query->where(function ($q) use ($areaId) {
                $q->where('is_featured', true)
                  ->orWhereJsonContains('featured_areas', $areaId);
            });
        }
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query, $areaId = null)
    {
        if ($areaId) {
            return $query->where(function ($q) use ($areaId) {
                $q->where('is_trending', true)
                  ->orWhereJsonContains('trending_areas', $areaId);
            });
        }
        return $query->where('is_trending', true);
    }

    public function isFeaturedInArea($areaId)
    {
        return $this->is_featured || in_array($areaId, $this->featured_areas ?? []);
    }

    public function isTrendingInArea($areaId)
    {
        return $this->is_trending || in_array($areaId, $this->trending_areas ?? []);
    }
}
