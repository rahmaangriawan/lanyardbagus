<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('site_title', 255)->nullable()->after('id');
            $table->unsignedInteger('logo_image_id')->nullable()->after('homepage_og_image_id');
            $table->unsignedInteger('favicon_image_id')->nullable()->after('logo_image_id');

            $table->foreign('logo_image_id', 'fk_site_settings_logo_image')
                ->references('id')->on('media')->nullOnDelete();

            $table->foreign('favicon_image_id', 'fk_site_settings_favicon_image')
                ->references('id')->on('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropForeign('fk_site_settings_logo_image');
            $table->dropForeign('fk_site_settings_favicon_image');
            $table->dropColumn(['site_title', 'logo_image_id', 'favicon_image_id']);
        });
    }
};
