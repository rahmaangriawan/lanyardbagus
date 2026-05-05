<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // Get existing categories
        $catLanyard = DB::table('product_categories')->where('slug', 'lanyard')->first();
        $catWristband = DB::table('product_categories')->where('slug', 'wristband')->first();
        $catKeychain = DB::table('product_categories')->where('slug', 'keychain')->first();
        $catIdCard = DB::table('product_categories')->where('slug', 'id-card')->first();
        $catCardHolder = DB::table('product_categories')->where('slug', 'card-holder')->first();

        $seedProducts = [
            [
                'category' => $catLanyard?->id,
                'name' => 'Lanyard Custom',
                'slug' => 'lanyard-custom',
                'description' => 'Lanyard custom dengan bahan polyester premium, sablon dan print sublimasi tajam, cocok untuk acara perusahaan, sekolah, dan komunitas.',
                'price' => 10000,
                'variants' => [
                    ['name' => 'Lanyard 1.5 cm', 'price_adjustment' => 0, 'is_active' => true],
                    ['name' => 'Lanyard 2 cm', 'price_adjustment' => 1500, 'is_active' => true],
                    ['name' => 'Lanyard 2.5 cm', 'price_adjustment' => 2500, 'is_active' => true],
                ],
                'accessories' => [
                    ['name' => 'ID Card Clip Standar', 'price' => 2000, 'is_active' => true],
                    ['name' => 'ID Card Clip Premium', 'price' => 3500, 'is_active' => true],
                    ['name' => 'Hook Metal', 'price' => 1500, 'is_active' => true],
                ],
            ],
            [
                'category' => $catWristband?->id,
                'name' => 'Wristband Lanyard Custom',
                'slug' => 'wristband-lanyard-custom',
                'description' => 'Wristband lanyard nyaman dipakai, tahan lama, ideal untuk tiket event, konser, dan kontrol akses.',
                'price' => 8000,
                'variants' => [
                    ['name' => 'Wristband 1.5 cm', 'price_adjustment' => 0, 'is_active' => true],
                    ['name' => 'Wristband 2 cm', 'price_adjustment' => 1000, 'is_active' => true],
                ],
                'accessories' => [
                    ['name' => 'Segel Plastik', 'price' => 500, 'is_active' => true],
                ],
            ],
            [
                'category' => $catKeychain?->id,
                'name' => 'Keychain Lanyard',
                'slug' => 'keychain-lanyard',
                'description' => 'Gantungan kunci lanyard praktis dan stylish untuk merchandise brand Anda.',
                'price' => 7000,
                'variants' => [
                    ['name' => 'Keychain Standar', 'price_adjustment' => 0, 'is_active' => true],
                    ['name' => 'Keychain Double Sided', 'price_adjustment' => 2000, 'is_active' => true],
                ],
                'accessories' => [],
            ],
            [
                'category' => $catIdCard?->id,
                'name' => 'ID Card PVC',
                'slug' => 'id-card-pvc',
                'description' => 'Kartu identitas PVC tebal, full color, laminasi doff/glossy, hasil cetak jernih dan tahan lama.',
                'price' => 15000,
                'variants' => [
                    ['name' => 'ID Card Single Side', 'price_adjustment' => 0, 'is_active' => true],
                    ['name' => 'ID Card Double Side', 'price_adjustment' => 5000, 'is_active' => true],
                ],
                'accessories' => [
                    ['name' => 'Lanyard + ID Card Clip', 'price' => 12000, 'is_active' => true],
                ],
            ],
            [
                'category' => $catCardHolder?->id,
                'name' => 'Card Holder',
                'slug' => 'card-holder',
                'description' => 'Holder kartu dengan model horizontal/vertikal, bahan lentur dan kuat untuk perlindungan kartu.',
                'price' => 5000,
                'variants' => [
                    ['name' => 'Card Holder Vertikal', 'price_adjustment' => 0, 'is_active' => true],
                    ['name' => 'Card Holder Horizontal', 'price_adjustment' => 0, 'is_active' => true],
                ],
                'accessories' => [],
            ],
        ];

        foreach ($seedProducts as $sp) {
            if (!$sp['category']) continue;

            $product = DB::table('products')->where('slug', $sp['slug'])->first();
            if (!$product) {
                DB::table('products')->insert([
                    'category_id' => $sp['category'],
                    'name' => $sp['name'],
                    'slug' => $sp['slug'],
                    'description' => $sp['description'],
                    'price' => $sp['price'],
                    'min_order' => 50,
                    'specs' => json_encode([]),
                    'images' => json_encode([]),
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $product = DB::table('products')->where('slug', $sp['slug'])->first();
            }

            // Seed variants
            foreach ($sp['variants'] as $v) {
                $exists = DB::table('product_variants')
                    ->where('product_id', $product->id)
                    ->where('name', $v['name'])
                    ->first();
                if (!$exists) {
                    DB::table('product_variants')->insert([
                        'product_id' => $product->id,
                        'name' => $v['name'],
                        'price_adjustment' => $v['price_adjustment'],
                        'image' => null,
                        'sort_order' => 0,
                        'is_active' => $v['is_active'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Seed accessories
            foreach ($sp['accessories'] as $a) {
                $exists = DB::table('product_accessories')
                    ->where('product_id', $product->id)
                    ->where('name', $a['name'])
                    ->first();
                if (!$exists) {
                    DB::table('product_accessories')->insert([
                        'product_id' => $product->id,
                        'name' => $a['name'],
                        'price' => $a['price'],
                        'image' => null,
                        'sort_order' => 0,
                        'is_active' => $a['is_active'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // No-op
    }
};
