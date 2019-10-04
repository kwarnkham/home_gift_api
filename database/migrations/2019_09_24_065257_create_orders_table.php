<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('promotion_id')->nullable()->default(null);
            $table->string('name');
            $table->string('mobile');
            $table->string('address');
            $table->string('payment');
            $table->integer('delivery_fees');
            $table->integer('amount');
            $table->enum('status',['pending', 'confirmed', 'on the way', 'delivered', 'canceled'])->default('pending');
            $table->text('c_note')->nullable()->default(null);
            $table->text('a_note')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('promotion_id')->references('id')->on('promotions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
