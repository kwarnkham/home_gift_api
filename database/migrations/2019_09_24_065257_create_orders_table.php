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
            $table->integer('amount')->nullable()->default(null);
            $table->enum('status',['unconfirmed', 'confirmed', 'on the way', 'delivered', 'canceled'])->default('unconfirmed');
            $table->text('c_note');
            $table->text('a_note');
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
