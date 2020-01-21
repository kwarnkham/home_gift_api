<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('item_id');
            $table->string('name');
            $table->string('ch_name');
            $table->integer('quantity');
            $table->integer('price');
            $table->text('description');
            $table->text('ch_description');
            $table->text('notice');
            $table->text('ch_notice');
            $table->double('weight');
            $table->string('location');
            $table->string('merchant');
            $table->timestamps();
            $table->unique(['order_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_order');
    }
}
