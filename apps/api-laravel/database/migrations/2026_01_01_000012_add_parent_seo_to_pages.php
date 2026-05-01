<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable()->after('id');
            $table->string('seo_title', 255)->nullable()->after('content');
            $table->text('meta_description')->nullable()->after('seo_title');
            $table->unsignedInteger('featured_image_id')->nullable()->after('meta_description');

            $table->foreign('parent_id', 'fk_pages_parent')
                  ->references('id')->on('pages')
                  ->nullOnDelete();

            $table->foreign('featured_image_id', 'fk_pages_featured_image')
                  ->references('id')->on('media')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('fk_pages_parent');
            $table->dropForeign('fk_pages_featured_image');
            $table->dropColumn(['parent_id', 'seo_title', 'meta_description', 'featured_image_id']);
        });
    }
};
