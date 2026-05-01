<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('site_settings')) {
            return;
        }

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('homepage_seo_title', 255)->nullable();
            $table->text('homepage_meta_description')->nullable();
            $table->unsignedInteger('homepage_og_image_id')->nullable();
            $table->string('google_site_verification', 255)->nullable();
            $table->string('bing_site_verification', 255)->nullable();
            $table->timestamps();

            $table->foreign('homepage_og_image_id', 'fk_site_settings_homepage_og_image')
                ->references('id')
                ->on('media')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropForeign('fk_site_settings_homepage_og_image');
        });

        Schema::dropIfExists('site_settings');
    }
};
