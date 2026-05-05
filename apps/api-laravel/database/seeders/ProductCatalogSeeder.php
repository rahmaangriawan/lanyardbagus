<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use App\Models\Product;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lanyard',
                'slug' => 'lanyard',
                'description' => 'Lanyard custom dengan bahan premium dan cetak tajam untuk identitas brand, event, maupun perusahaan.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Wristband Lanyard',
                'slug' => 'wristband',
                'description' => 'Wristband lanyard nyaman digunakan, cocok untuk tiket event, akses kontrol, dan komunitas.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Keychain Lanyard',
                'slug' => 'keychain',
                'description' => 'Gantungan kunci lanyard yang kuat dan estetik untuk merchandise maupun promosi.',
                'sort_order' => 3,
            ],
            [
                'name' => 'ID Card',
                'slug' => 'id-card',
                'description' => 'ID Card PVC tebal, laminasi rapi, bisa full color, cocok untuk karyawan, panitia, dan pelajar.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Card Holder',
                'slug' => 'card-holder',
                'description' => 'Holder kartu dengan berbagai model (horizontal/vertikal), bahan lentur dan tahan lama.',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            $category = ProductCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'is_active' => true,
                    'sort_order' => $cat['sort_order'],
                ]
            );

            // Seed a single sample product for each category if none
            if ($category->products()->count() === 0) {
                $nameMap = [
                    'lanyard' => 'Lanyard Custom',
                    'wristband' => 'Wristband Lanyard',
                    'keychain' => 'Keychain Lanyard',
                    'id-card' => 'ID Card',
                    'card-holder' => 'Card Holder',
                ];
                $descMap = [
                    'lanyard' => 'Lanyard custom bahan polyester/satin/nylon, sablon atau print sublim, warna tajam dan tahan lama.',
                    'wristband' => 'Wristband lanyard nyaman dan awet, cocok untuk identitas acara dan komunitas.',
                    'keychain' => 'Gantungan kunci lanyard praktis untuk promosi brand dan merchandise.',
                    'id-card' => 'Kartu identitas PVC, full color, laminasi doff/glossy, hasil cetak jernih.',
                    'card-holder' => 'Holder kartu model vertikal/horizontal, material lentur dan kuat.',
                ];
                $priceMap = [
                    'lanyard' => 10000,
                    'wristband' => 8000,
                    'keychain' => 7000,
                    'id-card' => 15000,
                    'card-holder' => 5000,
                ];

                $slug = $category->slug;
                $pname = $nameMap[$slug] ?? ($category->name . ' Custom');
                $pdesc = $descMap[$slug] ?? ('Produk ' . $category->name . ' berkualitas.');
                $price = $priceMap[$slug] ?? 10000;

                Product::create([
                    'category_id' => $category->id,
                    'name' => $pname,
                    'slug' => Str::slug($pname),
                    'description' => $pdesc,
                    'price' => $price,
                    'min_order' => 50,
                    'specs' => [
                        'Bahan' => in_array($slug, ['lanyard','wristband','keychain']) ? 'Polyester' : 'PVC',
                        'Warna' => 'Full Color',
                        'Cetak' => in_array($slug, ['lanyard']) ? 'Sublim' : '—',
                    ],
                    'images' => [],
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
            }
        }
    }
}
