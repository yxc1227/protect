<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHupunTradesInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hupun_trades_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trades_id')->comment("对应tradesid");
            $table->string('header')->default('')->comment('发票抬头');
            $table->string('invoice_detail')->default('')->comment('发票详情');
            $table->string('invoice_tax_id')->default('')->comment('税号');
            $table->integer("type")->default(0)->comment("类型:1=普通发票，2=增值税普通发票, 3=电子增票");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hupun_trades_invoice');
    }
}
