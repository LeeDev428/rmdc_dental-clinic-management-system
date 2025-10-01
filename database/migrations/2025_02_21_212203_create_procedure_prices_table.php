<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedurePricesTable extends Migration
{
    public function up()
    {
        Schema::create('procedure_prices', function (Blueprint $table) {
            $table->id();
            $table->string('procedure_name');
            $table->decimal('price', 10, 2); // Price with 2 decimal places
            $table->string('duration'); // Duration as a string (e.g., "30 minutes")
            $table->string('image_path')->nullable(); // Added image_path field
            $table->text('description')->nullable(); // Added description field
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('procedure_prices');
    }
}
