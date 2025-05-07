<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_employees', function (Blueprint $table) {
            $table->id();
            $table->date('in_date');
            $table->date('out_date');
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_employees');
    }
}; 