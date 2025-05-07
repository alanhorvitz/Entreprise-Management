<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reasons');
    }
}; 