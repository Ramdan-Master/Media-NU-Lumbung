<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::where('status', 'published')
            ->with(['author', 'category']);

        if ($request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $news = $query->latest('published_at')
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('news.index', compact('news', 'categories'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('status', 'published')
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        // Increment view count
        $news->increment('view_count');

        $relatedNews = News::where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(setting('news_related_count', 4))
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

        return view('news.show', compact('news', 'relatedNews', 'banners'));
    }

    public function area($slug)
    {
        $area = \App\Models\Area::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $news = News::where('area_id', $area->id)
            ->where('status', 'published')
            ->with(['author', 'category'])
            ->latest('published_at')
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('news.index', compact('news', 'categories', 'area'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $news = News::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%')
                  ->orWhere('excerpt', 'like', '%' . $query . '%');
            })
            ->with(['author', 'category'])
            ->latest('published_at')
            ->paginate(12);

        return view('news.search', compact('news', 'query'));
    }
}
