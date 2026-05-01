<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('title');
            $table->string('slug', 191)->unique();
            $table->longText('content');
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('slug', 'idx_pages_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
