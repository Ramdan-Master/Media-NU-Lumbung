<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'Media Organisasi', 'type' => 'text', 'description' => 'Nama website'],
            ['key' => 'site_tagline', 'value' => 'Platform Berita Terpercaya', 'type' => 'text', 'description' => 'Tagline website'],
            ['key' => 'site_description', 'value' => 'Media organisasi yang menyajikan berita terkini dan terpercaya untuk masyarakat Indonesia', 'type' => 'textarea', 'description' => 'Deskripsi website'],
            ['key' => 'site_keywords', 'value' => 'berita, media, organisasi, islam, indonesia, nu', 'type' => 'text', 'description' => 'Keywords SEO'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'info@media.com', 'type' => 'text', 'description' => 'Email kontak'],
            ['key' => 'contact_phone', 'value' => '021-12345678', 'type' => 'text', 'description' => 'Nomor telepon'],
            ['key' => 'contact_whatsapp', 'value' => '628123456789', 'type' => 'text', 'description' => 'WhatsApp'],
            ['key' => 'contact_address', 'value' => 'Jl. Kramat Raya No. 164, Jakarta Pusat 10430', 'type' => 'textarea', 'description' => 'Alamat'],
            
            // Social Media
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/mediaorganisasi', 'type' => 'text', 'description' => 'Facebook URL'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/mediaorganisasi', 'type' => 'text', 'description' => 'Twitter URL'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/mediaorganisasi', 'type' => 'text', 'description' => 'Instagram URL'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@mediaorganisasi', 'type' => 'text', 'description' => 'YouTube URL'],
            ['key' => 'social_linkedin', 'value' => '', 'type' => 'text', 'description' => 'LinkedIn URL'],
            ['key' => 'social_tiktok', 'value' => '', 'type' => 'text', 'description' => 'TikTok URL'],
            
            // Footer
            ['key' => 'footer_about', 'value' => 'Platform media organisasi yang menyajikan berita terkini dan terpercaya untuk masyarakat Indonesia.', 'type' => 'textarea', 'description' => 'Tentang di footer'],
            ['key' => 'footer_newsletter_description', 'value' => 'Dapatkan berita terbaru langsung ke email Anda.', 'type' => 'textarea', 'description' => 'Deskripsi newsletter di footer'],
            ['key' => 'footer_copyright', 'value' => '© ' . date('Y') . ' Media Organisasi. All rights reserved.', 'type' => 'text', 'description' => 'Copyright text'],

            // Home Sections
            ['key' => 'home_section_latest_news_title', 'value' => 'Berita Terbaru', 'type' => 'text', 'description' => 'Judul section berita terbaru di beranda'],
            ['key' => 'home_section_categories_title', 'value' => 'Kategori', 'type' => 'text', 'description' => 'Judul section kategori di beranda'],
            ['key' => 'home_section_banoms_title', 'value' => 'Badan Otonom', 'type' => 'text', 'description' => 'Judul section badan otonom di beranda'],

            // Features
            ['key' => 'enable_newsletter', 'value' => '1', 'type' => 'boolean', 'description' => 'Aktifkan newsletter'],
            ['key' => 'enable_comments', 'value' => '0', 'type' => 'boolean', 'description' => 'Aktifkan komentar'],
            ['key' => 'enable_banners', 'value' => '1', 'type' => 'boolean', 'description' => 'Aktifkan banner'],
            ['key' => 'enable_social_share', 'value' => '1', 'type' => 'boolean', 'description' => 'Aktifkan social share'],
            ['key' => 'posts_per_page', 'value' => '12', 'type' => 'text', 'description' => 'Jumlah berita per halaman'],

            // Content Limits
            ['key' => 'home_latest_news_count', 'value' => '9', 'type' => 'text', 'description' => 'Jumlah berita terbaru di beranda'],
            ['key' => 'home_trending_news_count', 'value' => '5', 'type' => 'text', 'description' => 'Jumlah berita trending di beranda'],
            ['key' => 'home_banoms_count', 'value' => '4', 'type' => 'text', 'description' => 'Jumlah badan otonom di beranda'],
            ['key' => 'news_related_count', 'value' => '4', 'type' => 'text', 'description' => 'Jumlah berita terkait'],
            ['key' => 'sidebar_latest_news_count', 'value' => '5', 'type' => 'text', 'description' => 'Jumlah berita terbaru di sidebar'],
            ['key' => 'footer_categories_count', 'value' => '5', 'type' => 'text', 'description' => 'Jumlah kategori di footer'],

            // Theme Colors (NU Theme)
            ['key' => 'primary_color', 'value' => '#006400', 'type' => 'text', 'description' => 'Warna primer tema NU'],
            ['key' => 'secondary_color', 'value' => '#228B22', 'type' => 'text', 'description' => 'Warna sekunder tema NU'],
            ['key' => 'accent_color', 'value' => '#32CD32', 'type' => 'text', 'description' => 'Warna aksen tema NU'],
            ['key' => 'text_color', 'value' => '#2F4F2F', 'type' => 'text', 'description' => 'Warna teks tema NU'],
            ['key' => 'background_color', 'value' => '#FFFFFF', 'type' => 'text', 'description' => 'Warna latar belakang tema NU'],

            // Google AdSense
            ['key' => 'adsense_code', 'value' => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5994175079280970" crossorigin="anonymous"></script>', 'type' => 'textarea', 'description' => 'Kode Google AdSense'],
        ];
        
        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
