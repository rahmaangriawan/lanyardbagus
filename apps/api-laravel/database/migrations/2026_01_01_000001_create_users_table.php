<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->boolean('remember_me')->default(false);
            $table->unsignedInteger('role_id');
            $table->timestamps();

            $table->index('role_id', 'idx_users_role_id');
            $table->foreign('role_id', 'fk_users_role')
                  ->references('id')->on('roles');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
