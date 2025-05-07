<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->text('summary');
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['user_id', 'project_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }
}; 