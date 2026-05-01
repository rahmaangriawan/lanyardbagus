<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('file_name');
            $table->string('url', 500);
            $table->string('mime_type', 100);
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->unsignedInteger('size');
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
