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
        Schema::table('material_reservation_histories', function (Blueprint $table) {
            // Add missing quantity column
            $table->integer('quantity')->after('employee_id');
            
            // Rename columns to match the expected structure
            $table->renameColumn('start_date', 'reservation_start');
            $table->renameColumn('end_date', 'reservation_end');
            $table->renameColumn('return_date', 'actual_return_date');
            $table->renameColumn('approver_id', 'approved_by');
            
            // Update status enum to include 'active'
            $table->enum('status', ['completed', 'overdue', 'active'])->default('completed')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_reservation_histories', function (Blueprint $table) {
            // Remove quantity column
            $table->dropColumn('quantity');
            
            // Rename columns back
            $table->renameColumn('reservation_start', 'start_date');
            $table->renameColumn('reservation_end', 'end_date');
            $table->renameColumn('actual_return_date', 'return_date');
            $table->renameColumn('approved_by', 'approver_id');
            
            // Revert status enum
            $table->string('status')->change();
        });
    }
};
