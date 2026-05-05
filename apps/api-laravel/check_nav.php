<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$setting = \App\Models\SiteSetting::first();

if (!$setting) {
    echo "No SiteSetting found\n";
    exit;
}

$homepageData = $setting->homepage_data ?? [];
$navLinks = $homepageData['navigation_links'] ?? [];

echo "Current navigation_links:\n";
print_r($navLinks);

// Check if Promo exists
$promoExists = false;
foreach ($navLinks as $link) {
    if (strtolower($link['label'] ?? '') === 'promo') {
        $promoExists = true;
        break;
    }
}

if (!$promoExists) {
    echo "\nPromo menu not found! Adding...\n";
    $navLinks[] = ['label' => 'Promo', 'href' => '/promo'];
    $homepageData['navigation_links'] = $navLinks;
    $setting->homepage_data = $homepageData;
    $setting->save();
    
    // Clear cache
    \Illuminate\Support\Facades\Cache::forget('homepage_data');
    \Illuminate\Support\Facades\Cache::forget('site_settings');
    
    echo "Promo menu added!\n";
    echo "Updated navigation_links:\n";
    print_r($navLinks);
} else {
    echo "\nPromo menu already exists!\n";
}
