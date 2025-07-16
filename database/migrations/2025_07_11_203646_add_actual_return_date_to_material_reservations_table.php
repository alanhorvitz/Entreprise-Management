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
        Schema::table('material_reservations', function (Blueprint $table) {
            $table->dateTime('actual_return_date')->nullable()->after('reservation_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_reservations', function (Blueprint $table) {
            $table->dropColumn('actual_return_date');
        });
    }
};
