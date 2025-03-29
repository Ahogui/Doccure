<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamTypesTable extends Migration
{
    public function up()
    {
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('category')->comment('laboratory, imaging, etc.');
            $table->string('duration')->nullable()->comment('Durée estimée');
            $table->string('sample_type')->nullable()->comment('Type d\'échantillon requis');
            $table->text('preparation_instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_types');
    }
}