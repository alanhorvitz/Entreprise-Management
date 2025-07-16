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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the incorrect foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Add the correct foreign key constraint
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the correct foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Restore the incorrect foreign key constraint (for rollback)
            $table->foreign('user_id')->references('id')->on('Users');
        });
    }
};
