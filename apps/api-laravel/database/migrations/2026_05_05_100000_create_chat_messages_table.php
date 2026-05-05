<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->string('user_name', 100)->nullable();
            $table->string('user_email', 100)->nullable();
            $table->text('message');
            $table->enum('role', ['user', 'assistant', 'system'])->default('user');
            $table->text('response')->nullable();
            $table->string('ai_model', 50)->nullable();
            $table->integer('tokens_used')->nullable();
            $table->boolean('is_training_data')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['session_id', 'created_at']);
            $table->index(['is_training_data', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
