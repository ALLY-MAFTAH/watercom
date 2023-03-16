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
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->date('date');
            $table->bigInteger('product_id');
            $table->bigInteger('stock_id');
            $table->bigInteger('good_id');
            $table->string('name');
            $table->string('seller');
            $table->string('measure');
            $table->string('type');
            $table->double('quantity');
            $table->double('volume');
            $table->string('unit');
            $table->double('price');
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
        Schema::dropIfExists('sales');
    }
};
