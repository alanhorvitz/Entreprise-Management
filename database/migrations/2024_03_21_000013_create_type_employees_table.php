<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('type_employees', function (Blueprint $table) {
            $table->id();
            $table->date('in_date')->nullable();
            $table->date('out_date')->nullable();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('type_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('type_employees');
    }
};
