<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->date('expiration_date')->nullable();
            $table->integer('quantity')->nullable();  // Make quantity nullable
            $table->string('unit')->default('pieces');  // Unit type (pieces, boxes, packs, bottles, etc.)
            $table->integer('items_per_unit')->default(1);  // How many items in one unit (e.g., 10 masks per box)
            $table->string('supplier');
            $table->enum('expiration_type', ['Expirable', 'Inexpirable'])->default('Expirable');
            $table->string('category');  // To store the category (e.g., "Dental Instruments", "Consumables")
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
