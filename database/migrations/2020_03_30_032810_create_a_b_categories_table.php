<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateABCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_b_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('a_category_id');
            $table->unsignedBigInteger('b_category_id');
            $table->unique(['a_category_id', 'b_category_id']);
            $table->foreign('a_category_id')->references('id')->on('a_categories');
            $table->foreign('b_category_id')->references('id')->on('b_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_b_categories');
    }
}
