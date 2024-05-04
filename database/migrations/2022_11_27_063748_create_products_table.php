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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stock_id');
            $table->string('name');
            $table->string('type');
            $table->string('unit');
            $table->double('volume');
            $table->unique(['stock_id', 'volume']);
            $table->string('measure');
            $table->double('price');
            $table->double('special_price')->default(0);
            $table->double('refill_price')->default(0);
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
