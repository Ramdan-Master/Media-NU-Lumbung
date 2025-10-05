<?php

namespace App\Filament\Pages;

use App\Models\SystemSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class WebsiteSettings extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Pengaturan Website';
    
    protected static ?string $navigationGroup = 'Pengaturan';
    
    protected static ?int $navigationSort = 1;
    
    protected static string $view = 'filament.pages.website-settings';
    
    protected static ?string $title = 'Pengaturan Website';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill($this->getSettings());
    }
    
    protected function getSettings(): array
    {
        return [
            'site_name' => setting('site_name', 'Media Organisasi'),
            'site_tagline' => setting('site_tagline', 'Platform Berita Terpercaya'),
            'site_description' => setting('site_description', 'Media organisasi yang menyajikan berita terkini dan terpercaya'),
            'site_keywords' => setting('site_keywords', 'berita, media, organisasi, islam'),
            'site_logo' => setting('site_logo'),
            'site_favicon' => setting('site_favicon'),
            
            'contact_email' => setting('contact_email', 'info@media.com'),
            'contact_phone' => setting('contact_phone', '021-12345678'),
            'contact_address' => setting('contact_address', 'Jakarta, Indonesia'),
            'contact_whatsapp' => setting('contact_whatsapp'),
            
            'social_facebook' => setting('social_facebook'),
            'social_twitter' => setting('social_twitter'),
            'social_instagram' => setting('social_instagram'),
            'social_youtube' => setting('social_youtube'),
            'social_linkedin' => setting('social_linkedin'),
            'social_tiktok' => setting('social_tiktok'),
            
            'footer_about' => setting('footer_about', 'Platform media organisasi yang menyajikan berita terkini dan terpercaya untuk masyarakat Indonesia.'),
            'footer_newsletter_description' => setting('footer_newsletter_description', 'Dapatkan berita terbaru langsung ke email Anda.'),
            'footer_copyright' => setting('footer_copyright', '© ' . date('Y') . ' Media Organisasi. All rights reserved.'),

            'home_section_latest_news_title' => setting('home_section_latest_news_title', 'Berita Terbaru'),
            'home_section_categories_title' => setting('home_section_categories_title', 'Kategori'),
            'home_section_banoms_title' => setting('home_section_banoms_title', 'Badan Otonom'),

            'enable_newsletter' => setting('enable_newsletter', true),
            'enable_comments' => setting('enable_comments', false),
            'enable_banners' => setting('enable_banners', true),
            'posts_per_page' => setting('posts_per_page', 12),
            'enable_social_share' => setting('enable_social_share', true),

            // Home Page Content Limits
            'home_latest_news_count' => setting('home_latest_news_count', 9),
            'home_trending_news_count' => setting('home_trending_news_count', 5),
            'home_banoms_count' => setting('home_banoms_count', 4),

            // News Page Settings
            'news_related_count' => setting('news_related_count', 4),
            'sidebar_latest_news_count' => setting('sidebar_latest_news_count', 5),

            // Footer Settings
            'footer_categories_count' => setting('footer_categories_count', 5),

            // Theme Colors (NU Theme)
            'primary_color' => setting('primary_color', '#006400'),
            'secondary_color' => setting('secondary_color', '#228B22'),
            'accent_color' => setting('accent_color', '#32CD32'),
            'text_color' => setting('text_color', '#2F4F2F'),
            'background_color' => setting('background_color', '#FFFFFF'),

            // Google AdSense
            'adsense_code' => setting('adsense_code'),
        ];
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Umum')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Section::make('Informasi Website')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label('Nama Website')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Media Organisasi'),
                                        
                                        Forms\Components\TextInput::make('site_tagline')
                                            ->label('Tagline')
                                            ->maxLength(255)
                                            ->placeholder('Platform Berita Terpercaya'),
                                        
                                        Forms\Components\Textarea::make('site_description')
                                            ->label('Deskripsi Website')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->placeholder('Deskripsi singkat tentang website'),
                                        
                                        Forms\Components\Textarea::make('site_keywords')
                                            ->label('Keywords (SEO)')
                                            ->rows(2)
                                            ->placeholder('berita, media, organisasi, islam')
                                            ->helperText('Pisahkan dengan koma'),
                                    ])
                                    ->columns(1),
                                
                                Forms\Components\Section::make('Logo & Favicon')
                                    ->schema([
                                        Forms\Components\FileUpload::make('site_logo')
                                            ->label('Logo Website')
                                            ->image()
                                            ->directory('settings')
                                            ->maxSize(2048)
                                            ->imageEditor()
                                            ->helperText('Ukuran rekomendasi: 200x50px'),
                                        
                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('settings')
                                            ->maxSize(512)
                                            ->helperText('Ukuran rekomendasi: 32x32px atau 64x64px'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Kontak')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\Section::make('Informasi Kontak')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->placeholder('info@media.com'),
                                        
                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label('Telepon')
                                            ->tel()
                                            ->placeholder('021-12345678'),
                                        
                                        Forms\Components\TextInput::make('contact_whatsapp')
                                            ->label('WhatsApp')
                                            ->tel()
                                            ->placeholder('628123456789')
                                            ->helperText('Format: 628xxx (tanpa +)'),
                                        
                                        Forms\Components\Textarea::make('contact_address')
                                            ->label('Alamat')
                                            ->rows(3)
                                            ->placeholder('Jl. Contoh No. 123, Jakarta'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Media Sosial')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Link Media Sosial')
                                    ->description('Masukkan URL lengkap untuk setiap media sosial')
                                    ->schema([
                                        Forms\Components\TextInput::make('social_facebook')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/username')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        
                                        Forms\Components\TextInput::make('social_twitter')
                                            ->label('Twitter / X')
                                            ->url()
                                            ->placeholder('https://twitter.com/username')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        
                                        Forms\Components\TextInput::make('social_instagram')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/username')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        
                                        Forms\Components\TextInput::make('social_youtube')
                                            ->label('YouTube')
                                            ->url()
                                            ->placeholder('https://youtube.com/@username')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        
                                        Forms\Components\TextInput::make('social_linkedin')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->placeholder('https://linkedin.com/company/username')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        
                                        Forms\Components\TextInput::make('social_tiktok')
                                            ->label('TikTok')
                                            ->url()
                                            ->placeholder('https://tiktok.com/@username')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Footer')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Section::make('Pengaturan Footer')
                                    ->schema([
                                        Forms\Components\Textarea::make('footer_about')
                                            ->label('Tentang (Footer)')
                                            ->rows(4)
                                            ->placeholder('Deskripsi singkat untuk footer website'),

                                        Forms\Components\Textarea::make('footer_newsletter_description')
                                            ->label('Deskripsi Newsletter')
                                            ->rows(2)
                                            ->placeholder('Dapatkan berita terbaru langsung ke email Anda.'),

                                        Forms\Components\TextInput::make('footer_copyright')
                                            ->label('Copyright Text')
                                            ->placeholder('© 2024 Media Organisasi. All rights reserved.'),
                                    ])
                                    ->columns(1),
                            ]),

                        Forms\Components\Tabs\Tab::make('Beranda')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Forms\Components\Section::make('Judul Section Beranda')
                                    ->schema([
                                        Forms\Components\TextInput::make('home_section_latest_news_title')
                                            ->label('Judul Section Berita Terbaru')
                                            ->placeholder('Berita Terbaru'),

                                        Forms\Components\TextInput::make('home_section_categories_title')
                                            ->label('Judul Section Kategori')
                                            ->placeholder('Kategori'),

                                        Forms\Components\TextInput::make('home_section_banoms_title')
                                            ->label('Judul Section Badan Otonom')
                                            ->placeholder('Badan Otonom'),
                                    ])
                                    ->columns(1),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Konten')
                            ->icon('heroicon-o-queue-list')
                            ->schema([
                                Forms\Components\Section::make('Batas Konten Beranda')
                                    ->description('Atur jumlah konten yang ditampilkan di halaman beranda')
                                    ->schema([
                                        Forms\Components\TextInput::make('home_latest_news_count')
                                            ->label('Jumlah Berita Terbaru')
                                            ->numeric()
                                            ->minValue(3)
                                            ->maxValue(20)
                                            ->default(9)
                                            ->helperText('Jumlah berita terbaru yang ditampilkan di beranda'),

                                        Forms\Components\TextInput::make('home_trending_news_count')
                                            ->label('Jumlah Berita Trending')
                                            ->numeric()
                                            ->minValue(3)
                                            ->maxValue(10)
                                            ->default(5)
                                            ->helperText('Jumlah berita trending yang ditampilkan di beranda'),

                                        Forms\Components\TextInput::make('home_banoms_count')
                                            ->label('Jumlah Badan Otonom')
                                            ->numeric()
                                            ->minValue(2)
                                            ->maxValue(12)
                                            ->default(4)
                                            ->helperText('Jumlah badan otonom yang ditampilkan di beranda'),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Batas Konten Berita')
                                    ->description('Atur jumlah konten yang ditampilkan di halaman berita')
                                    ->schema([
                                        Forms\Components\TextInput::make('news_related_count')
                                            ->label('Jumlah Berita Terkait')
                                            ->numeric()
                                            ->minValue(2)
                                            ->maxValue(8)
                                            ->default(4)
                                            ->helperText('Jumlah berita terkait yang ditampilkan di halaman detail berita'),

                                        Forms\Components\TextInput::make('sidebar_latest_news_count')
                                            ->label('Jumlah Berita Terbaru di Sidebar')
                                            ->numeric()
                                            ->minValue(3)
                                            ->maxValue(10)
                                            ->default(5)
                                            ->helperText('Jumlah berita terbaru yang ditampilkan di sidebar'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Batas Konten Footer')
                                    ->description('Atur jumlah konten yang ditampilkan di footer')
                                    ->schema([
                                        Forms\Components\TextInput::make('footer_categories_count')
                                            ->label('Jumlah Kategori di Footer')
                                            ->numeric()
                                            ->minValue(3)
                                            ->maxValue(10)
                                            ->default(5)
                                            ->helperText('Jumlah kategori yang ditampilkan di footer'),
                                    ])
                                    ->columns(1),
                            ]),

                        Forms\Components\Tabs\Tab::make('Tema')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\Section::make('Warna Tema NU')
                                    ->description('Sesuaikan warna website sesuai ciri khas NU (Nahdlatul Ulama)')
                                    ->schema([
                                        Forms\Components\ColorPicker::make('primary_color')
                                            ->label('Warna Primer')
                                            ->default('#006400')
                                            ->helperText('Warna utama untuk tombol, link, dan elemen penting'),

                                        Forms\Components\ColorPicker::make('secondary_color')
                                            ->label('Warna Sekunder')
                                            ->default('#228B22')
                                            ->helperText('Warna pendukung untuk elemen sekunder'),

                                        Forms\Components\ColorPicker::make('accent_color')
                                            ->label('Warna Aksen')
                                            ->default('#32CD32')
                                            ->helperText('Warna untuk highlight dan aksen'),

                                        Forms\Components\ColorPicker::make('text_color')
                                            ->label('Warna Teks')
                                            ->default('#2F4F2F')
                                            ->helperText('Warna untuk teks utama'),

                                        Forms\Components\ColorPicker::make('background_color')
                                            ->label('Warna Latar Belakang')
                                            ->default('#FFFFFF')
                                            ->helperText('Warna latar belakang halaman'),
                                    ])
                                    ->columns(3),
                            ]),

                        Forms\Components\Tabs\Tab::make('Fitur')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Forms\Components\Section::make('Pengaturan Fitur')
                                    ->schema([
                                        Forms\Components\Toggle::make('enable_newsletter')
                                            ->label('Aktifkan Newsletter')
                                            ->helperText('Tampilkan form subscribe newsletter di footer')
                                            ->default(true),

                                        Forms\Components\Toggle::make('enable_comments')
                                            ->label('Aktifkan Komentar')
                                            ->helperText('Izinkan pengunjung berkomentar di berita')
                                            ->default(false),

                                        Forms\Components\Toggle::make('enable_social_share')
                                            ->label('Aktifkan Social Share')
                                            ->helperText('Tampilkan tombol share ke media sosial')
                                            ->default(true),

                                        Forms\Components\TextInput::make('posts_per_page')
                                            ->label('Berita Per Halaman')
                                            ->numeric()
                                            ->minValue(6)
                                            ->maxValue(50)
                                            ->default(12)
                                            ->helperText('Jumlah berita yang ditampilkan per halaman'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Iklan')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Section::make('Google AdSense')
                                    ->description('Masukkan kode Google AdSense untuk menampilkan iklan di website')
                                    ->schema([
                                        Forms\Components\Textarea::make('adsense_code')
                                            ->label('Kode AdSense')
                                            ->rows(6)
                                            ->placeholder('<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXX" crossorigin="anonymous"></script>')
                                            ->helperText('Tempel kode AdSense lengkap di sini. Kosongkan untuk menonaktifkan iklan.'),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            set_setting($key, $value);
        }
        
        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Pengaturan website telah diperbarui.')
            ->send();
    }
}
