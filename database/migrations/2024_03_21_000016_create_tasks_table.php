<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('description');
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->date('due_date');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('current_status', ['todo', 'in_progress', 'completed'])->default('todo');
            $table->date('start_date');
            $table->enum('status', ['rejected', 'pending_approval', 'approved'])->default('pending_approval');
            $table->boolean('is_repetitive')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}; 