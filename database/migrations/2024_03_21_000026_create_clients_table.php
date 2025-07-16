<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code_client', 8)->unique();
            $table->enum('client_type', ['individual', 'society'])->nullable();
            $table->enum('society_type', ['company', 'enterprise', 'foundation', 'association', 'cooperative', 'gov foundation'])->nullable();
            $table->string('enterprise_sector')->nullable();
            $table->string('tax_identification_number_enterprise')->nullable();
            $table->string('legal_representative_position_enterprise')->nullable();
            $table->string('name_client')->nullable();
            $table->string('prenom_client')->nullable();
            $table->string('email_client')->unique()->nullable();
            $table->string('telephone_client')->nullable();
            $table->string('secondary_telephone_client')->nullable();
            $table->string('address_client')->nullable();
            $table->string('address_client2')->nullable();
            $table->string('cin_client')->nullable();
            $table->string('genre_client')->nullable();
            $table->string('sector_of_work_client')->nullable();
            $table->date('date_of_birth_client')->nullable();
            $table->dateTime('registration_datetime_client')->nullable();
            $table->timestamp('registration_datetime')->nullable();
            $table->string('enterprise_name')->nullable();
            $table->string('ice_enterprise')->nullable();
            $table->string('telephone_enterprise')->nullable();
            $table->string('address_enterprise')->nullable();
            $table->string('address_enterprise2')->nullable();
            $table->string('secondary_telephone_enterprise')->nullable();
            $table->string('legal_representative_name_enterprise')->nullable();
            $table->string('legal_representative_prenom_enterprise')->nullable();
            $table->string('legal_representative_cin_enterprise')->nullable();
            $table->string('legal_representative_nationality_enterprise')->nullable();
            $table->string('legal_representative_email_enterprise')->nullable();
            $table->dateTime('registration_datetime_enterprise')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            // Add foreign key constraints
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}; 