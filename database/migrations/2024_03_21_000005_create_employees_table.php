<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('cin')->unique();
            $table->text('cin_attachment')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('address')->nullable();
            $table->string('personal_num')->nullable();
            $table->string('professional_num')->nullable();
            $table->string('pin')->nullable();
            $table->string('puk')->nullable();
            $table->double('salary')->nullable();
            $table->double('hourly_salary')->nullable();
            $table->boolean('is_project')->default(false);
            $table->string('hours')->nullable();
            $table->string('ice')->nullable();
            $table->string('professional_email')->nullable();
            $table->string('cnss')->nullable();
            $table->string('assurance')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}; 