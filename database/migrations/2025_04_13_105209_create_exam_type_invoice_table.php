<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamTypeInvoiceTable extends Migration
{
    public function up()
    {
        Schema::create('exam_type_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('exam_type_id');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('exam_type_id')->references('id')->on('exam_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_type_invoice');
    }
}
