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
       Schema::create('company_images', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // 'header' ou 'footer' (ou autres types)
            $table->string('filename');       // Nom du fichier, ex: header.png
            $table->string('path');           // Chemin relatif dans storage, ex: 'images/header.png'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_images');
    }
};
