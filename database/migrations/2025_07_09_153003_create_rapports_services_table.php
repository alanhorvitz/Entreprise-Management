<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapportsServicesTable extends Migration
{
    /**
     * La méthode "up" est utilisée pour créer la table et ajouter les colonnes.
     */
    public function up()
    {
        Schema::create('rapports_services', function (Blueprint $table) {
            $table->id(); // La clé primaire auto-incrémentée pour la table

            // Clé étrangère pour le client
            $table->unsignedBigInteger('client_id');
            // Clé étrangère pour l'administrateur
            $table->unsignedBigInteger('admin_id')->nullable();
            // Clé étrangère pour le service
            $table->unsignedBigInteger('service_id');
            // Clé étrangère pour l'archive du service
            $table->unsignedBigInteger('service_archived_id')->nullable();

            $table->timestamps(); // Colonnes created_at et updated_at

            // Définition des relations avec les clés étrangères
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('service_archived_id')->references('id')->on('services_archive')->onDelete('set null');
        });
    }

    /**
     * La méthode "down" est utilisée pour supprimer la table en cas de rollback.
     */
    public function down()
    {
        Schema::dropIfExists('rapports_services');
    }
}
