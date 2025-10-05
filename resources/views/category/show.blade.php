@extends('layouts.app')

@section('title', $category->name . ' - Media Organisasi')

@section('content')
<div class="container py-4">
    <!-- Category Header -->
    <div class="card shadow-sm mb-4" style="border-left: 5px solid {{ $category->color }};">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <i class="{{ $category->icon }} fa-3x me-3" style="color: {{ $category->color }};"></i>
                <div>
                    <h2 class="mb-1">{{ $category->name }}</h2>
                    <p class="text-muted mb-0">{{ $category->description }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- News List -->
    @if($news->count() > 0)
        <div class="row g-4">
            @foreach($news as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        @if($item->featured_image)
                            <img src="{{ asset('storage/' . $item->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->title }}">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-image fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="category-badge" style="background-color: {{ $category->color }}; color: white;">
                                {{ $category->name }}
                            </span>
                            <h5 class="card-title mt-2">
                                <a href="{{ route('news.show', $item->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $item->title }}
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                {{ Str::limit($item->excerpt, 100) }}
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
            Belum ada berita dalam kategori ini.
        </div>
    @endif
</div>
@endsection
