<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\News;
use App\Models\Banner;
use App\Models\Banom;
use App\Models\BanomManagement;
use App\Models\NewsletterSubscriber;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MediaOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@media.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'bio' => 'Administrator Media Organisasi',
        ]);

        // Create Editor User
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@media.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'phone' => '081234567891',
            'bio' => 'Editor Media Organisasi',
            'area_id' => 1, // Assign to first area (Desa Lumbugsari)
        ]);

        // Create Categories
        $categories = [
            [
                'name' => 'Berita Utama',
                'slug' => 'berita-utama',
                'description' => 'Berita utama dan terkini',
                'icon' => 'fas fa-newspaper',
                'color' => '#ef4444',
                'sort_order' => 1,
            ],
            [
                'name' => 'Artikel',
                'slug' => 'artikel',
                'description' => 'Artikel dan opini',
                'icon' => 'fas fa-file-alt',
                'color' => '#3b82f6',
                'sort_order' => 2,
            ],
            [
                'name' => 'Khutbah',
                'slug' => 'khutbah',
                'description' => 'Khutbah Jumat dan ceramah',
                'icon' => 'fas fa-mosque',
                'color' => '#10b981',
                'sort_order' => 3,
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Berita pendidikan',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#f59e0b',
                'sort_order' => 4,
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
                'description' => 'Berita ekonomi dan bisnis',
                'icon' => 'fas fa-chart-line',
                'color' => '#8b5cf6',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create Tags
        $tags = ['Islam', 'Organisasi', 'Sosial', 'Budaya', 'Politik', 'Kesehatan', 'Teknologi', 'Lingkungan'];
        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => \Illuminate\Support\Str::slug($tagName),
            ]);
        }

        // Create News
        $newsData = [
            [
                'title' => 'Selamat Datang di Media Organisasi',
                'excerpt' => 'Platform media organisasi yang menyajikan berita terkini dan terpercaya',
                'content' => '<p>Selamat datang di Media Organisasi, platform berita yang menyajikan informasi terkini, terpercaya, dan berkualitas. Kami berkomitmen untuk memberikan berita yang akurat dan berimbang kepada masyarakat.</p><p>Media Organisasi hadir sebagai wadah informasi yang mengedepankan nilai-nilai kejujuran, transparansi, dan profesionalisme dalam setiap pemberitaan.</p>',
                'status' => 'published',
                'is_featured' => true,
                'is_trending' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Pentingnya Literasi Digital di Era Modern',
                'excerpt' => 'Literasi digital menjadi kunci sukses di era teknologi informasi',
                'content' => '<p>Di era digital seperti sekarang, literasi digital menjadi sangat penting. Kemampuan untuk memahami, menggunakan, dan mengevaluasi informasi digital adalah keterampilan yang harus dimiliki setiap orang.</p><p>Literasi digital tidak hanya tentang kemampuan menggunakan teknologi, tetapi juga tentang kemampuan berpikir kritis terhadap informasi yang diterima.</p>',
                'status' => 'published',
                'is_featured' => false,
                'is_trending' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Peran Organisasi dalam Pembangunan Masyarakat',
                'excerpt' => 'Organisasi memiliki peran penting dalam membangun masyarakat yang lebih baik',
                'content' => '<p>Organisasi masyarakat memiliki peran strategis dalam pembangunan. Melalui berbagai program dan kegiatan, organisasi dapat memberdayakan masyarakat dan meningkatkan kualitas hidup.</p><p>Kolaborasi antara organisasi, pemerintah, dan masyarakat menjadi kunci keberhasilan pembangunan yang berkelanjutan.</p>',
                'status' => 'published',
                'is_featured' => false,
                'is_trending' => false,
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($newsData as $index => $data) {
            $news = News::create(array_merge($data, [
                'author_id' => $index === 0 ? $admin->id : $editor->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'view_count' => rand(100, 1000),
            ]));

            // Attach random tags
            $news->tags()->attach(Tag::inRandomOrder()->limit(rand(2, 4))->pluck('id'));
        }

        // Create Banoms
        $banoms = [
            [
                'name' => 'Muslimat NU',
                'slug' => 'muslimat-nu',
                'description' => '<p>Muslimat Nahdlatul Ulama adalah organisasi perempuan terbesar di Indonesia yang bergerak dalam bidang pendidikan, sosial, dan pemberdayaan perempuan.</p>',
                'website' => 'https://muslimat.or.id',
                'email' => 'info@muslimat.or.id',
                'phone' => '021-1234567',
                'sort_order' => 1,
            ],
            [
                'name' => 'Fatayat NU',
                'slug' => 'fatayat-nu',
                'description' => '<p>Fatayat NU adalah organisasi perempuan muda Nahdlatul Ulama yang fokus pada pemberdayaan perempuan muda dan pendidikan.</p>',
                'website' => 'https://fatayat.or.id',
                'email' => 'info@fatayat.or.id',
                'phone' => '021-1234568',
                'sort_order' => 2,
            ],
            [
                'name' => 'Ansor',
                'slug' => 'ansor',
                'description' => '<p>Gerakan Pemuda Ansor adalah organisasi kepemudaan Nahdlatul Ulama yang bergerak dalam bidang kepemudaan dan olahraga.</p>',
                'website' => 'https://ansor.or.id',
                'email' => 'info@ansor.or.id',
                'phone' => '021-1234569',
                'sort_order' => 3,
            ],
        ];

        foreach ($banoms as $banomData) {
            $banom = Banom::create($banomData);
            
            // Add management for each banom
            if ($banom->slug === 'muslimat-nu') {
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Hj. Khofifah Indar Parawansa',
                    'position' => 'Ketua Umum',
                    'period' => '2021-2026',
                    'email' => 'ketua@muslimat.or.id',
                    'phone' => '021-12345678',
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
                
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Hj. Masruroh',
                    'position' => 'Sekretaris Umum',
                    'period' => '2021-2026',
                    'is_active' => true,
                    'sort_order' => 2,
                ]);
                
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Hj. Lilik Nur Qomariyah',
                    'position' => 'Bendahara Umum',
                    'period' => '2021-2026',
                    'is_active' => true,
                    'sort_order' => 3,
                ]);
            } elseif ($banom->slug === 'fatayat-nu') {
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Anggia Ermarini',
                    'position' => 'Ketua Umum',
                    'period' => '2020-2025',
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
                
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Nur Rofiah',
                    'position' => 'Sekretaris Umum',
                    'period' => '2020-2025',
                    'is_active' => true,
                    'sort_order' => 2,
                ]);
            } elseif ($banom->slug === 'ansor') {
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Yaqut Cholil Qoumas',
                    'position' => 'Ketua Umum',
                    'period' => '2018-2023',
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
                
                BanomManagement::create([
                    'banom_id' => $banom->id,
                    'name' => 'Ahmad Zainul Hamdi',
                    'position' => 'Sekretaris Jenderal',
                    'period' => '2018-2023',
                    'is_active' => true,
                    'sort_order' => 2,
                ]);
            }
        }

        // Create Newsletter Subscribers
        for ($i = 1; $i <= 10; $i++) {
            NewsletterSubscriber::create([
                'email' => "subscriber{$i}@example.com",
                'name' => "Subscriber {$i}",
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Data seeder berhasil dijalankan!');
        $this->command->info('📧 Admin: admin@media.com | Password: password');
        $this->command->info('📧 Editor: editor@media.com | Password: password');
    }
}
