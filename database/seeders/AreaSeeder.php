<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Desa Lumbugsari',
                'slug' => 'desa-lumbugsari',
                'description' => 'Berita dan informasi dari Desa Lumbugsari',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#007bff',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Desa Rawa',
                'slug' => 'desa-rawa',
                'description' => 'Berita dan informasi dari Desa Rawa',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#28a745',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Desa Karangasem',
                'slug' => 'desa-karangasem',
                'description' => 'Berita dan informasi dari Desa Karangasem',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#dc3545',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(
                ['slug' => $area['slug']],
                $area
            );
        }
    }
}
