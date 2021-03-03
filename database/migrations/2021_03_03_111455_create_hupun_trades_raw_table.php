<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHupunTradesRawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hupun_trades_raw', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('timestamp')->comment("录入批次时间戳");
            $table->text('raw')->comment("请求获取到的有效结果集");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hupun_trades_raw');
    }
}
