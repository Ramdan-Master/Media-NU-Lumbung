<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\Banom;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredNews = News::where('status', 'published')
            ->where('is_featured', true)
            ->with(['author', 'category'])
            ->latest('published_at')
            ->first();

        $trendingNews = News::where('status', 'published')
            ->where('is_trending', true)
            ->with(['author', 'category'])
            ->orderBy('view_count', 'desc')
            ->take(setting('home_trending_news_count', 5))
            ->get();

        $latestNews = News::where('status', 'published')
            ->with(['author', 'category'])
            ->latest('published_at')
            ->take(setting('home_latest_news_count', 9))
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('news')
            ->get();

        $banoms = Banom::where('is_active', true)
            ->orderBy('sort_order')
            ->take(setting('home_banoms_count', 4))
            ->get();

        $banners = Banner::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('sort_order')
            ->get();

        return view('home', compact(
            'featuredNews',
            'trendingNews',
            'latestNews',
            'categories',
            'banoms',
            'banners'
        ));
    }
}
