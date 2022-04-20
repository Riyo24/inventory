<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualanJasaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_jasa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jasa_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('jasa_id')->references('id')->on('jasa')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_jasa');
    }
}
