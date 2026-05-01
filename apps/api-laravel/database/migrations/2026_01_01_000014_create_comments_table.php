<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('author_name', 191);
            $table->string('author_email', 191);
            $table->string('author_url', 255)->nullable();
            $table->text('content');
            $table->enum('status', ['PENDING', 'APPROVED', 'SPAM', 'TRASH'])->default('PENDING');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
            $table->foreign('parent_id')->references('id')->on('comments')->nullOnDelete();

            $table->index(['post_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
