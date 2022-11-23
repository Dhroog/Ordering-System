<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_cart_id');
            $table->unsignedBigInteger('restaurant_id');
            $table->decimal('total')->default(0);
            $table->decimal('delivery_cost')->default(0);
            $table->decimal('tax')->default(0);
            $table->timestamps();
            $table->foreign('main_cart_id')
                ->references('id')
                ->on('main_carts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
