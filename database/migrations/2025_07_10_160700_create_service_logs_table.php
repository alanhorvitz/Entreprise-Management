<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
{
    Schema::create('service_logs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('service_id');
        $table->string('field_changed');
        $table->string('old_value')->nullable();
        $table->string('new_value')->nullable();
        $table->unsignedBigInteger('changed_by')->nullable(); // utilisateur ou null pour système
        $table->timestamps();

        $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        // Tu peux aussi ajouter foreign key pour changed_by si tu veux
    });
}

public function down()
{
    Schema::dropIfExists('service_logs');
}

    
};
