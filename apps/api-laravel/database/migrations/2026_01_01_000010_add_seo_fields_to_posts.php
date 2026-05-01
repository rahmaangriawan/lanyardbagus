<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('seo_title', 255)->nullable()->after('excerpt');
            $table->text('meta_description')->nullable()->after('seo_title');
            $table->unsignedInteger('featured_image_id')->nullable()->after('meta_description');

            $table->foreign('featured_image_id', 'fk_posts_featured_image')
                  ->references('id')->on('media')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('fk_posts_featured_image');
            $table->dropColumn(['seo_title', 'meta_description', 'featured_image_id']);
        });
    }
};
