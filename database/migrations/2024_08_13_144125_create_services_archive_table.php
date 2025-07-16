<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesArchiveTable extends Migration
{
    public function up()
    {
        Schema::create('services_archive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            $table->string('service_name');
            $table->boolean('is_subscription')->nullable();
            $table->string('service_code', 8);

            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('tva', 8, 2)->default(20.00);
            $table->decimal('total_price', 8, 2)->nullable();
            $table->text('service_description')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('dernier_paiement')->nullable();
            $table->string('services_status')->nullable();

            $table->enum('payment_status', ['payé', 'non payé', 'subscription end'])->default('non payé');
            $table->enum('mode_payment', ['espece', 'cheque', 'effet', 'verment', 'versement', 'tpe', 'composation']);
            $table->enum('validation_status', ['validé', 'non-validé', 'en cours'])->default('non-validé');

            $table->timestamps();
            $table->dateTime('service_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->string('invoice_number')->nullable();

            //$table->enum('subscription_duration', ['1', '3', '6', '12', '24'])->nullable();
                $table->string('subscription_duration')->nullable();

            $table->integer('remains_subscription')->nullable();

            $table->string('month_year')->nullable(); // format: MM-YYYY

            // ✅ Nouvelle contrainte d’unicité sur client_id + service_code + month_year
            $table->unique(['client_id', 'service_code', 'month_year'], 'services_archive_client_code_month_unique');
              $table->string('devis_file')->nullable();
        $table->string('order_file')->nullable();
        $table->string('facture_file')->nullable();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('services_archive');
    }
}
