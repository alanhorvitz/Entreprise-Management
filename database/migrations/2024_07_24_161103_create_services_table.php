<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('service_name');
            $table->boolean('is_subscription');
            $table->string('service_code', 8)->unique();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('tva', 8, 2)->default(20.00);
            $table->decimal('total_price', 8, 2)->nullable();
            $table->text('service_description')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->dateTime('dernier_paiement')->nullable();
            $table->string('services_status');
            $table->enum('payment_status', ['payé', 'non payé', 'subscription end', 'subscription ending soon'])->default('non payé');
            $table->enum('mode_payment', ['espece', 'cheque', 'effet', 'verment', 'versement', 'tpe', 'composation']);
            $table->enum('validation_status', ['validé', 'non-validé', 'en cours'])->default('non-validé');
            $table->timestamps();
            $table->dateTime('service_start_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('devis_code')->nullable()->unique(); // corrigé ici
            $table->string('code_bon_commande')->nullable()->unique();
            $table->boolean('renewed_subscription')->default(0);
            $table->string('subscription_duration')->nullable(); // enum remplacé par string
            $table->string('devis_file')->nullable();
            $table->string('order_file')->nullable();
            $table->string('facture_file')->nullable();
            $table->integer('remains_subscription')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->dateTime('new_date_subscription_end_after')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->boolean('is_excluded_payment')->default(false);
            $table->integer('fixed_payment_day')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}
