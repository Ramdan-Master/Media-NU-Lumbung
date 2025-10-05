@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $query . ' - Media Organisasi')

@section('content')
<div class="container py-4">
    <!-- Search Header -->
    <div class="mb-4">
        <h2><i class="fas fa-search me-2"></i>Hasil Pencarian</h2>
        <p class="text-muted">Menampilkan hasil untuk: <strong>"{{ $query }}"</strong></p>
        <p class="text-muted">Ditemukan {{ $news->total() }} berita</p>
    </div>
    
    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input type="text" 
                           name="q" 
                           class="form-control form-control-lg" 
                           placeholder="Cari berita..."
                           value="{{ $query }}"
                           required>
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Search Results -->
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
                                {{ Str::limit($item->excerpt, 100) }}
                            </p>
                            <div class="news-meta">
                                <i class="fas fa-user me-1"></i> {{ $item->author->name }}
                                <br>
                                <i class="fas fa-calendar me-1"></i> {{ $item->published_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $news->appends(['q' => $query])->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Tidak ada hasil yang ditemukan untuk "<strong>{{ $query }}</strong>". 
            Silakan coba dengan kata kunci lain.
        </div>
        
        <!-- Suggestions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Berita Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach(\App\Models\News::where('status', 'published')->latest('published_at')->take(6)->get() as $latest)
                        <div class="col-md-4">
                            <div class="d-flex">
                                @if($latest->featured_image)
                                    <img src="{{ asset('storage/' . $latest->featured_image) }}" 
                                         class="rounded me-3" 
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         alt="{{ $latest->title }}">
                                @endif
                                <div>
                                    <h6>
                                        <a href="{{ route('news.show', $latest->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ Str::limit($latest->title, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $latest->published_at->format('d M Y') }}
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
@endsection
