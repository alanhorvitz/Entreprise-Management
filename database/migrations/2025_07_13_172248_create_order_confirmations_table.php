<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('confirmed_by')->nullable()->references('id')->on('employees')->onDelete('cascade');
            $table->string('product_name');
            $table->string('client_name');
            $table->string('client_number', 20);
            $table->text('client_address');
            $table->dateTime('confirmation_date');
            $table->enum('status', ['confirmed', 'cancelled', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_confirmations');
    }
};
