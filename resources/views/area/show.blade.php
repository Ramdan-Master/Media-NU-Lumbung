@extends('layouts.app')

@section('title', $area->name . ' - Media Organisasi')

@section('content')
<!-- Area Banner -->
@if($banner)
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if($banner->link)
                    <a href="{{ $banner->link }}" target="_blank">
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             class="img-fluid w-100 rounded"
                             alt="{{ $banner->title }}"
                             style="max-height: 200px; object-fit: cover;">
                    </a>
                @else
                    <img src="{{ asset('storage/' . $banner->image) }}"
                         class="img-fluid w-100 rounded"
                         alt="{{ $banner->title }}"
                         style="max-height: 200px; object-fit: cover;">
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Featured News -->
            <div class="col-lg-8">
                @if($featuredNews)
                    <div class="card shadow-sm h-100">
                        @if($featuredNews->featured_image)
                            <img src="{{ asset('storage/' . $featuredNews->featured_image) }}"
                                 class="card-img-top"
                                 style="height: 400px; object-fit: cover;"
                                 alt="{{ $featuredNews->title }}">
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
                        <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Trending di {{ $area->name }}</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($trendingNews as $index => $trending)
                            <div class="p-3 {{ $loop->first ? '' : 'border-top' }}">
                                <div class="d-flex">
                                    @if($trending->featured_image)
                                        <a href="{{ route('news.show', $trending->slug) }}" class="text-decoration-none">
                                            <img src="{{ asset('storage/' . $trending->featured_image) }}"
                                                 class="rounded me-3"
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 alt="{{ $trending->title }}">
                                        </a>
                                    @else
                                        <a href="{{ route('news.show', $trending->slug) }}" class="text-decoration-none">
                                            <div class="rounded me-3 bg-secondary d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('news.show', $trending->slug) }}"
                                               class="text-decoration-none text-dark">
                                                {{ Str::limit($trending->title, 60) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $trending->published_at->format('d M Y') }}
                                            <i class="fas fa-eye ms-2 me-1"></i>{{ number_format($trending->view_count) }}
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

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-tags me-2"></i>Kategori di {{ $area->name }}</h3>
            <a href="{{ route('news.area', $area->slug) }}" class="btn btn-outline-primary">
                Lihat Semua Berita <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

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
@endif

<!-- Latest News Section -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-newspaper me-2"></i>Berita Terbaru di {{ $area->name }}</h3>
            <a href="{{ route('news.area', $area->slug) }}" class="btn btn-outline-primary">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($news as $article)
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm h-100">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $article->title }}">
                        @endif
                        <div class="card-body">
                            <span class="category-badge bg-primary text-white">
                                {{ $article->category->name }}
                            </span>
                            <h5 class="card-title mt-2">
                                <a href="{{ route('news.show', $article->slug) }}"
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($article->title, 60) }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small">{{ Str::limit($article->excerpt, 100) }}</p>
                            <div class="news-meta">
                                <i class="fas fa-user me-1"></i> {{ $article->author->name }}
                                <i class="fas fa-calendar ms-3 me-1"></i> {{ $article->published_at->format('d M Y') }}
                                <i class="fas fa-eye ms-3 me-1"></i> {{ number_format($article->view_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $news->links() }}
        </div>
    </div>
</section>
@endsection
