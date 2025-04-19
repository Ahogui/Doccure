<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoicesTableChangeClientToPatient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->renameColumn('client_id', 'patient_id');
        // Ou si la colonne n'existe pas encore :
        // $table->foreignId('patient_id')->constrained('patients');
    });
}

public function down()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->renameColumn('patient_id', 'client_id');
    });
}
}
