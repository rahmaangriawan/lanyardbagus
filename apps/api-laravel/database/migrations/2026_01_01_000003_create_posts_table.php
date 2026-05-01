<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('title');
            $table->string('slug', 191)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->enum('status', ['DRAFT', 'PUBLISHED'])->default('DRAFT');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('category_id')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at'], 'idx_posts_status_created_at');
            $table->index('slug', 'idx_posts_slug');
            $table->index('author_id', 'idx_posts_author_id');
            $table->index('category_id', 'idx_posts_category_id');

            $table->foreign('author_id', 'fk_posts_author')
                  ->references('id')->on('users');
            $table->foreign('category_id', 'fk_posts_category')
                  ->references('id')->on('categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
