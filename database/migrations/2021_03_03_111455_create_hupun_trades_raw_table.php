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
            $table->unsignedInteger('timestamp')->comment('录入批次时间戳');
            $table->longText('raw')->comment('请求获取到的有效结果集');
            $table->boolean('resolved')->default(false)->comment('是否被解析处理，0未解析，1已解析');
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
