<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-newspaper me-2"></i>
                    {{ setting('site_name', 'Media Organisasi') }}
                </h5>
                <p class="text-white-50">
                    {{ setting('footer_about', 'Platform media organisasi yang menyajikan berita terkini dan terpercaya untuk masyarakat Indonesia.') }}
                </p>
                <div class="mt-3">
                    @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" target="_blank" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    @endif
                    @if(setting('social_twitter'))
                        <a href="{{ setting('social_twitter') }}" target="_blank" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    @endif
                    @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" target="_blank" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    @endif
                    @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" target="_blank" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                    @endif
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="mb-3">Kategori</h5>
                <ul class="list-unstyled">
                    @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(setting('footer_categories_count', 5))->get() as $category)
                        <li class="mb-2">
                            <a href="{{ route('category.show', $category->slug) }}" class="text-white-50 text-decoration-none">
                                <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="col-md-4 mb-4">
                <h5 class="mb-3">Newsletter</h5>
                <p class="text-white-50">{{ setting('footer_newsletter_description', 'Dapatkan berita terbaru langsung ke email Anda.') }}</p>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="input-group mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <hr class="my-4 bg-white opacity-25">
        
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-white-50">
                    {{ setting('footer_copyright', '© ' . date('Y') . ' Media Organisasi. All rights reserved.') }}
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="{{ route('home') }}" class="text-white-50 text-decoration-none me-3">Beranda</a>
                <a href="{{ route('news.index') }}" class="text-white-50 text-decoration-none me-3">Berita</a>
                <a href="{{ route('banom.index') }}" class="text-white-50 text-decoration-none">Badan Otonom</a>
            </div>
        </div>
    </div>
</footer>
