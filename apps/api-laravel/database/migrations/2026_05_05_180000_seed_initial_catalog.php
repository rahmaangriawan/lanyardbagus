<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $cats = [
            ['name' => 'Lanyard', 'slug' => 'lanyard', 'description' => 'Lanyard custom dengan bahan premium dan cetak tajam untuk identitas brand, event, maupun perusahaan.', 'sort_order' => 1],
            ['name' => 'Wristband Lanyard', 'slug' => 'wristband', 'description' => 'Wristband lanyard nyaman digunakan, cocok untuk tiket event, akses kontrol, dan komunitas.', 'sort_order' => 2],
            ['name' => 'Keychain Lanyard', 'slug' => 'keychain', 'description' => 'Gantungan kunci lanyard yang kuat dan estetik untuk merchandise maupun promosi.', 'sort_order' => 3],
            ['name' => 'ID Card', 'slug' => 'id-card', 'description' => 'ID Card PVC tebal, laminasi rapi, bisa full color, cocok untuk karyawan, panitia, dan pelajar.', 'sort_order' => 4],
            ['name' => 'Card Holder', 'slug' => 'card-holder', 'description' => 'Holder kartu dengan berbagai model (horizontal/vertikal), bahan lentur dan tahan lama.', 'sort_order' => 5],
        ];

        $catIds = [];
        foreach ($cats as $c) {
            $existing = DB::table('product_categories')->where('slug', $c['slug'])->first();
            if (!$existing) {
                DB::table('product_categories')->insert([
                    'name' => $c['name'],
                    'slug' => $c['slug'],
                    'description' => $c['description'],
                    'image' => null,
                    'is_active' => true,
                    'sort_order' => $c['sort_order'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $existing = DB::table('product_categories')->where('slug', $c['slug'])->first();
            }
            $catIds[$c['slug']] = $existing->id;
        }

        $products = [
            ['category_slug' => 'lanyard', 'name' => 'Lanyard Custom', 'description' => 'Lanyard custom bahan polyester/satin/nylon, sablon atau print sublim, warna tajam dan tahan lama.', 'price' => 10000],
            ['category_slug' => 'wristband', 'name' => 'Wristband Lanyard', 'description' => 'Wristband lanyard nyaman dan awet, cocok untuk identitas acara dan komunitas.', 'price' => 8000],
            ['category_slug' => 'keychain', 'name' => 'Keychain Lanyard', 'description' => 'Gantungan kunci lanyard praktis untuk promosi brand dan merchandise.', 'price' => 7000],
            ['category_slug' => 'id-card', 'name' => 'ID Card', 'description' => 'Kartu identitas PVC, full color, laminasi doff/glossy, hasil cetak jernih.', 'price' => 15000],
            ['category_slug' => 'card-holder', 'name' => 'Card Holder', 'description' => 'Holder kartu model vertikal/horizontal, material lentur dan kuat.', 'price' => 5000],
        ];

        foreach ($products as $p) {
            $categoryId = $catIds[$p['category_slug']] ?? null;
            if (!$categoryId) continue;

            $exists = DB::table('products')
                ->where('category_id', $categoryId)
                ->where('slug', Str::slug($p['name']))
                ->first();

            if (!$exists) {
                DB::table('products')->insert([
                    'category_id' => $categoryId,
                    'name' => $p['name'],
                    'slug' => Str::slug($p['name']),
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'min_order' => 50,
                    'specs' => json_encode([
                        'Bahan' => in_array($p['category_slug'], ['lanyard','wristband','keychain']) ? 'Polyester' : 'PVC',
                        'Warna' => 'Full Color',
                    ]),
                    'images' => json_encode([]),
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // No-op: keep seeded data
    }
};
