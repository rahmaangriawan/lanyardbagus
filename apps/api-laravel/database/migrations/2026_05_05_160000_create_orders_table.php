<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('items'); // JSON: [{name, qty, size, price}]
            $table->integer('amount'); // in rupiah
            $table->integer('fee')->default(0);
            $table->integer('total_payment')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_number')->nullable(); // QR string or VA number
            $table->enum('status', ['pending', 'waiting_payment', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('redirect_url')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
