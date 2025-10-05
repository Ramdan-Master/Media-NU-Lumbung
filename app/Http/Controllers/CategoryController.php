<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $news = $category->news()
            ->where('status', 'published')
            ->with(['author', 'category'])
            ->latest('published_at')
            ->paginate(12);

        return view('category.show', compact('category', 'news'));
    }
}
