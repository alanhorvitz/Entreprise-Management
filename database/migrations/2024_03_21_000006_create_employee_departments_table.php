<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_departments');
    }
}; 