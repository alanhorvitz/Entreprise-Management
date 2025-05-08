<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->nullable();
            $table->string('cin')->unique();
            $table->text('cin_attachment');
            $table->string('profile_picture');
            $table->string('address');
            $table->string('personal_num');
            $table->string('professional_num')->nullable();
            $table->string('pin')->nullable();
            $table->string('puk')->nullable();
            $table->double('salary')->nullable();
            $table->double('hourly_salary')->nullable();
            $table->boolean('is_project')->nullable()->default(false);
            $table->boolean('is_anapec')->nullable()->default(false);
            $table->string('hours')->nullable();
            $table->string('ice')->nullable();
            $table->string('professional_email')->nullable();
            $table->string('cnss')->nullable();
            $table->string('training_type')->nullable();
            $table->string('school')->nullable();
            $table->string('assurance')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('status_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('employees');
    }
};
