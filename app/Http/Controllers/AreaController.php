<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function show($slug)
    {
        $area = Area::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $news = $area->news()
            ->where('status', 'published')
            ->with(['author', 'category', 'area'])
            ->latest('published_at')
            ->paginate(12);

        // Get banner for this area
        $banner = \App\Models\Banner::active()
            ->where('position', 'area_top')
            ->where('area_id', $area->id)
            ->orderBy('sort_order')
            ->first();

        // Get featured news from this area
        $featuredNews = $area->news()
            ->where('status', 'published')
            ->where(function ($query) use ($area) {
                $query->where('is_featured', true)
                      ->orWhereJsonContains('featured_areas', $area->id);
            })
            ->with(['author', 'category', 'area'])
            ->latest('published_at')
            ->first();

        // Get trending news from this area
        $trendingNews = $area->news()
            ->where('status', 'published')
            ->where(function ($query) use ($area) {
                $query->where('is_trending', true)
                      ->orWhereJsonContains('trending_areas', $area->id);
            })
            ->with(['author', 'category', 'area'])
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        // Get categories that have news in this area
        $categories = \App\Models\Category::whereHas('news', function($query) use ($area) {
            $query->where('status', 'published')
                  ->where('area_id', $area->id);
        })
        ->withCount(['news' => function($query) use ($area) {
            $query->where('status', 'published')
                  ->where('area_id', $area->id);
        }])
        ->orderBy('sort_order')
        ->get();

        return view('area.show', compact('area', 'news', 'banner', 'featuredNews', 'trendingNews', 'categories'));
    }
}
