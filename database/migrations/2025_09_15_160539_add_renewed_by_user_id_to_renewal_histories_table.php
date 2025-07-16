<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('renewal_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('renewed_by_user_id')->nullable()->after('new_end_date');

            // Si tu veux ajouter la clé étrangère vers la table admins
            $table->foreign('renewed_by_user_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renewal_histories', function (Blueprint $table) {
            //
        });
    }
};
