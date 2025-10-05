@extends('layouts.app')

@section('title', 'Beranda - Media Organisasi')

@section('content')
<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Featured News -->
            <div class="col-lg-8">
                @if($featuredNews)
                    <div class="card shadow-sm h-100">
                        @if($featuredNews->featured_image)
                            <a href="{{ route('news.show', $featuredNews->slug) }}" class="text-decoration-none">
                                <img src="{{ asset('storage/' . $featuredNews->featured_image) }}"
                                     class="card-img-top hero-featured-img"
                                     alt="{{ $featuredNews->title }}">
                            </a>
                        @endif
                        <div class="card-body">
                            <span class="category-badge bg-primary text-white">
                                {{ $featuredNews->category->name }}
                            </span>
                            <h2 class="card-title mt-3">
                                <a href="{{ route('news.show', $featuredNews->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $featuredNews->title }}
                                </a>
                            </h2>
                            <p class="card-text text-muted">{{ Str::limit($featuredNews->excerpt, 200) }}</p>
                            <div class="news-meta">
                                <i class="fas fa-user me-1"></i> {{ $featuredNews->author->name }}
                                <i class="fas fa-calendar ms-3 me-1"></i> {{ $featuredNews->published_at->format('d M Y') }}
                                <i class="fas fa-eye ms-3 me-1"></i> {{ number_format($featuredNews->view_count) }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Trending News -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Trending</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($trendingNews as $index => $news)
                            <div class="p-3 {{ $index < count($trendingNews) - 1 ? 'border-bottom' : '' }}">
                                <div class="d-flex">
                                    @if($news->featured_image)
                                        <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none">
                                            <img src="{{ asset('storage/' . $news->featured_image) }}"
                                                 class="rounded me-3"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 alt="{{ $news->title }}">
                                        </a>
                                    @else
                                        <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none">
                                            <div class="rounded me-3 bg-secondary d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('news.show', $news->slug) }}"
                                               class="text-decoration-none text-dark">
                                                {{ Str::limit($news->title, 60) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $news->published_at->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Home Top Banners -->
@if(setting('enable_banners', true) && $banners->where('position', 'home_top')->count() > 0)
<section class="py-3">
    <div class="container">
        <div class="row g-3">
            @foreach($banners->where('position', 'home_top')->sortBy('sort_order') as $banner)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm position-relative">
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="text-decoration-none">
                        @endif
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             class="card-img-top"
                             alt="{{ $banner->title }}"
                             style="height: 200px; object-fit: cover;">
                        @if($banner->title || $banner->description)
                            <div class="card-img-overlay d-flex flex-column justify-content-end text-white"
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                @if($banner->title)
                                    <h5 class="card-title mb-1">{{ $banner->title }}</h5>
                                @endif
                                @if($banner->description)
                                    <p class="card-text small mb-0">{{ Str::limit($banner->description, 100) }}</p>
                                @endif
                            </div>
                        @endif
                        @if($banner->link)
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest News -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-newspaper me-2"></i>{{ setting('home_section_latest_news_title', 'Berita Terbaru') }}</h3>
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($latestNews as $news)
                <div class="col-6 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        @if($news->featured_image)
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none">
                                <img src="{{ asset('storage/' . $news->featured_image) }}"
                                     class="card-img-top"
                                     alt="{{ $news->title }}">
                            </a>
                        @else
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none">
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            </a>
                        @endif
                        <div class="card-body">
                            <span class="category-badge" style="background-color: {{ $news->category->color }}; color: white;">
                                {{ $news->category->name }}
                            </span>
                            <h5 class="card-title mt-2">
                                <a href="{{ route('news.show', $news->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($news->title, 60) }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                {{ Str::limit($news->excerpt, 100) }}
                            </p>
                            <div class="news-meta">
                                <i class="fas fa-user me-1"></i> {{ $news->author->name }}
                                <br>
                                <i class="fas fa-calendar me-1"></i> {{ $news->published_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="mb-4"><i class="fas fa-th-large me-2"></i>{{ setting('home_section_categories_title', 'Kategori') }}</h3>
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('category.show', $category->slug) }}" 
                       class="text-decoration-none">
                        <div class="card shadow-sm text-center h-100">
                            <div class="card-body">
                                <i class="{{ $category->icon }} fa-3x mb-3" 
                                   style="color: {{ $category->color }}"></i>
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ $category->news_count }} Berita
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Badan Otonom Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-building me-2"></i>{{ setting('home_section_banoms_title', 'Badan Otonom') }}</h3>
            <a href="{{ route('banom.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($banoms as $banom)
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('banom.show', $banom->slug) }}" 
                       class="text-decoration-none">
                        <div class="card shadow-sm text-center h-100">
                            <div class="card-body">
                                @if($banom->logo)
                                    <img src="{{ asset('storage/' . $banom->logo) }}" 
                                         class="img-fluid mb-3" 
                                         style="max-height: 100px;"
                                         alt="{{ $banom->name }}">
                                @else
                                    <i class="fas fa-building fa-3x text-primary mb-3"></i>
                                @endif
                                <h5 class="card-title">{{ $banom->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit(strip_tags($banom->description), 80) }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
