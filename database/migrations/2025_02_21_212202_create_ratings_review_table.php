<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsReviewTable extends Migration // Updated class name
{
    public function up()
    {
        Schema::create('ratings_review', function (Blueprint $table) { // Updated table name
            $table->id();
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings_review'); // Updated table name
    }
}
