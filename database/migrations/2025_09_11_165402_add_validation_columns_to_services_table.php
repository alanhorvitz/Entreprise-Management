<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
            }

            if (!Schema::hasColumn('services', 'deleted_by')) {
                $table->unsignedBigInteger('deleted_by')->nullable();
            }

            if (!Schema::hasColumn('services', 'date_statut_validé')) {
                $table->timestamp('date_statut_validé')->nullable();
            }

            if (!Schema::hasColumn('services', 'validated_by')) {
                $table->unsignedBigInteger('validated_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'validated_by')) {
                $table->dropColumn('validated_by');
            }

            if (Schema::hasColumn('services', 'date_statut_validé')) {
                $table->dropColumn('date_statut_validé');
            }

            if (Schema::hasColumn('services', 'updated_by')) {
                $table->dropColumn('updated_by');
            }

            if (Schema::hasColumn('services', 'deleted_by')) {
                $table->dropColumn('deleted_by');
            }
        });
    }
};
