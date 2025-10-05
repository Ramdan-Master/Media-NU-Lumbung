@extends('layouts.app')

@section('title', $news->meta_title ?? $news->title . ' - Media Organisasi')

@section('meta')
    <meta name="description" content="{{ $news->meta_description ?? Str::limit($news->excerpt, 160) }}">
    <meta name="keywords" content="{{ $news->meta_keywords ?? $news->tags->pluck('name')->implode(', ') }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ Str::limit($news->excerpt, 160) }}">
    @if($news->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $news->featured_image) }}">
    @endif
    <meta property="og:url" content="{{ route('news.show', $news->slug) }}">
    <meta property="og:type" content="article">
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <!-- Category Badge -->
                    <span class="category-badge mb-3" style="background-color: {{ $news->category->color }}; color: white;">
                        <i class="{{ $news->category->icon }} me-1"></i>
                        {{ $news->category->name }}
                    </span>
                    
                    <!-- Title -->
                    <h1 class="mt-3 mb-3">{{ $news->title }}</h1>
                    
                    <!-- Meta Info -->
                    <div class="news-meta mb-4 pb-3 border-bottom">
                        <i class="fas fa-user me-1"></i> {{ $news->author->name }}
                        <i class="fas fa-calendar ms-3 me-1"></i> {{ $news->published_at->format('d F Y, H:i') }}
                        <i class="fas fa-eye ms-3 me-1"></i> {{ number_format($news->view_count) }} views
                    </div>
                    
                    <!-- Featured Image -->
                    @if($news->featured_image)
                        <img src="{{ asset('storage/' . $news->featured_image) }}" 
                             class="img-fluid rounded mb-4" 
                             alt="{{ $news->title }}">
                    @endif
                    
                    <!-- Content -->
                    <div class="content">
                        {!! $news->content !!}
                    </div>
                    
                    <!-- Tags -->
                    @if($news->tags->count() > 0)
                        <div class="mt-4 pt-3 border-top">
                            <strong class="me-2">Tags:</strong>
                            @foreach($news->tags as $tag)
                                <span class="badge bg-secondary me-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Share Buttons -->
                    <div class="mt-4 pt-3 border-top">
                        <strong class="me-2">Bagikan:</strong>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-primary me-2">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $news->slug)) }}&text={{ urlencode($news->title) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info me-2">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . route('news.show', $news->slug)) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </article>
            
            <!-- Related News -->
            @if($relatedNews->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-newspaper me-2"></i>Berita Terkait</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($relatedNews as $related)
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        @if($related->featured_image)
                                            <img src="{{ asset('storage/' . $related->featured_image) }}" 
                                                 class="rounded me-3" 
                                                 style="width: 100px; height: 100px; object-fit: cover;"
                                                 alt="{{ $related->title }}">
                                        @endif
                                        <div>
                                            <h6>
                                                <a href="{{ route('news.show', $related->slug) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ Str::limit($related->title, 60) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $related->published_at->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Latest News Sidebar -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Berita Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    @foreach(\App\Models\News::where('status', 'published')->latest('published_at')->take(setting('sidebar_latest_news_count', 5))->get() as $index => $latest)
                        <div class="p-3 {{ $index < 4 ? 'border-bottom' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('news.show', $latest->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($latest->title, 60) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $latest->published_at->format('d M Y') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Categories Sidebar -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach(\App\Models\Category::where('is_active', true)->withCount('news')->orderBy('sort_order')->get() as $category)
                            <a href="{{ route('category.show', $category->slug) }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="{{ $category->icon }} me-2"></i>
                                    {{ $category->name }}
                                </span>
                                <span class="badge bg-primary rounded-pill">{{ $category->news_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar Banners -->
            @if(setting('enable_banners', true) && $banners->where('position', 'sidebar_top')->count() > 0)
                @foreach($banners->where('position', 'sidebar_top')->sortBy('sort_order') as $banner)
                    <div class="card shadow-sm mb-3">
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank">
                        @endif
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             class="card-img-top"
                             alt="{{ $banner->title }}">
                        @if($banner->link)
                            </a>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
    
    .content p {
        margin-bottom: 1rem;
    }
    
    .content h2, .content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
</style>
@endpush
