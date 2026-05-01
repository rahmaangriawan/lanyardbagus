<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        if (! Schema::hasColumn('site_settings', 'homepage_data')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->json('homepage_data')->nullable()->after('favicon_image_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('site_settings') && Schema::hasColumn('site_settings', 'homepage_data')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropColumn('homepage_data');
            });
        }
    }
};
