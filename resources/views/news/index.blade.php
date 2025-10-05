@extends('layouts.app')

@section('title', 'Semua Berita - Media Organisasi')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="mb-4">
        <h2><i class="fas fa-newspaper me-2"></i>@if(isset($area)) Berita {{ $area->name }} @else Semua Berita @endif</h2>
        <p class="text-muted">@if(isset($area)) Temukan berita terbaru dari {{ $area->name }} @else Temukan berita terbaru dan terpercaya @endif</p>
    </div>
    
    <div class="row">
        <!-- News List -->
        <div class="col-12 col-lg-9 order-2 order-lg-1">
            @if($news->count() > 0)
                <div class="row g-4">
                    @foreach($news as $item)
                        <div class="col-6 col-md-6">
                            <div class="card shadow-sm h-100">
                                @if($item->featured_image)
                                    <a href="{{ route('news.show', $item->slug) }}" class="text-decoration-none">
                                        <img src="{{ asset('storage/' . $item->featured_image) }}"
                                             class="card-img-top"
                                             alt="{{ $item->title }}">
                                    </a>
                                @else
                                    <a href="{{ route('news.show', $item->slug) }}" class="text-decoration-none">
                                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-white"></i>
                                        </div>
                                    </a>
                                @endif
                                <div class="card-body">
                                    <span class="category-badge" style="background-color: {{ $item->category->color }}; color: white;">
                                        {{ $item->category->name }}
                                    </span>
                                    <h5 class="card-title mt-2">
                                        <a href="{{ route('news.show', $item->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $item->title }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted">
                                        {{ Str::limit($item->excerpt, 120) }}
                                    </p>
                                    <div class="news-meta">
                                        <i class="fas fa-user me-1"></i> {{ $item->author->name }}
                                        <br>
                                        <i class="fas fa-calendar me-1"></i> {{ $item->published_at->format('d M Y') }}
                                        <i class="fas fa-eye ms-2 me-1"></i> {{ number_format($item->view_count) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $news->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Tidak ada berita yang ditemukan.
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-12 col-lg-3 order-1 order-lg-2">
            <!-- Search -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-search me-2"></i>Cari Berita
                    </h5>
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" 
                                   name="q" 
                                   class="form-control" 
                                   placeholder="Kata kunci..."
                                   value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Categories -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-th-large me-2"></i>Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
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
            
            <!-- Popular News -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Populer</h5>
                </div>
                <div class="card-body p-0">
                    @foreach(\App\Models\News::where('status', 'published')->orderBy('view_count', 'desc')->take(5)->get() as $index => $popular)
                        <div class="p-3 {{ $index < 4 ? 'border-bottom' : '' }}">
                            <div class="d-flex">
                                @if($popular->featured_image)
                                    <a href="{{ route('news.show', $popular->slug) }}" class="text-decoration-none">
                                        <img src="{{ asset('storage/' . $popular->featured_image) }}"
                                             class="rounded me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             alt="{{ $popular->title }}">
                                    </a>
                                @else
                                    <a href="{{ route('news.show', $popular->slug) }}" class="text-decoration-none">
                                        <div class="rounded me-3 bg-secondary d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    </a>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('news.show', $popular->slug) }}"
                                           class="text-decoration-none text-dark">
                                            {{ Str::limit($popular->title, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>
                                        {{ number_format($popular->view_count) }} views
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
@endsection
