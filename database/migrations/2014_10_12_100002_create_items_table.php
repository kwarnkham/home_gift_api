<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('ch_name');
            $table->string('mm_name');
            $table->integer('price');
            $table->text('description');
            $table->text('ch_description');
            $table->text('mm_description');
            $table->text('notice')->nullable()->default(null);
            $table->text('ch_notice')->nullable()->default(null);
            $table->text('mm_notice')->nullable()->default(null);
            $table->string('weight');
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('location_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
