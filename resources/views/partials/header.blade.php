<header>
    <!-- Top Bar -->
    <div class="bg-dark text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small>
                        <i class="fas fa-envelope me-2"></i> {{ setting('contact_email', 'info@media.com') }}
                        <i class="fas fa-phone ms-3 me-2"></i> {{ setting('contact_phone', '021-12345678') }}
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" target="_blank" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if(setting('social_twitter'))
                        <a href="{{ setting('social_twitter') }}" target="_blank" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" target="_blank" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" target="_blank" class="text-white"><i class="fab fa-youtube"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                <i class="fas fa-newspaper me-2"></i>
                {{ setting('site_name', 'Media Organisasi') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                            Berita
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            Kategori
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">
                                        <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('banom.*') ? 'active' : '' }}" href="{{ route('banom.index') }}">
                            Badan Otonom
                        </a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex ms-3" action="{{ route('search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Cari berita..." value="{{ request('q') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Areas Section -->
    <section class="bg-light py-2 areas-section">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                @foreach(\App\Models\Area::where('is_active', true)->orderBy('sort_order')->get() as $area)
                    <a href="{{ route('area.show', $area->slug) }}"
                       class="text-decoration-none text-dark fw-semibold px-3 py-2 rounded-pill"
                       style="background-color: {{ $area->color }}20; border: 1px solid {{ $area->color }}40;">
                        <i class="{{ $area->icon }} me-2"></i>{{ $area->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</header>
