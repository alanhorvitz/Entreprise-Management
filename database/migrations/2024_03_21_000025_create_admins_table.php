<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prenom');
            $table->integer('age');
            $table->date('date_of_birth');
            $table->string('cin')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'administrateur']);
            $table->rememberToken()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // Create default super admin
        DB::table('admins')->insert([
            'name' => 'Super',
            'prenom' => 'Admin',
            'age' => 30,
            'date_of_birth' => '1994-01-01',
            'cin' => 'SA123456',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin@example.com'),
            'role' => 'super_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}; 