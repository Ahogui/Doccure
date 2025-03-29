<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('invoice_date');
            $table->decimal('grand_total', 10, 2);
            $table->enum('status', ['paid', 'unpaid', 'cancelled'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'check'])->default('cash');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}