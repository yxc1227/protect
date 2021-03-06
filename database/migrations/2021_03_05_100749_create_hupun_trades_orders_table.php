<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHupunTradesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hupun_trades_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trades_id')->comment('对应tradesid');
            $table->string('currency_code')->default('')->comment('原始货币种类');
            $table->decimal('currency_sum')->default(0)->comment('原始货币金额');
            $table->integer('has_refund')->default(0)->comment('是否退款');
            $table->integer('is_gift')->default(0)->comment('明细是否赠品');
            $table->boolean('is_package')->default(false)->comment('是否组合商品');
            $table->string('item_image_url')->default('')->comment('商品线上的图片url');
            $table->string('item_name')->default('')->comment('商品名称');
            $table->string('item_platform_url')->default('')->comment('商品线上的详情链接');
            $table->string('oln_item_id')->default('')->comment('线上商品id');
            $table->string('oln_item_name')->default('')->comment('线上商品名称');
            $table->string('oln_sku_id')->default('')->comment('线上规格id');
            $table->string('oln_sku_name')->default('')->comment('线上商品规格属性');
            $table->integer('oln_status')->default(0)->comment('线上状态:1:等待付款 2:等待发货 ,部分发货 3:已完成 4:已关闭 5: 等待确认 6:已签收 0: 未建交易');
            $table->string('order_id')->default('')->comment('明细id，单据级唯一');
            $table->decimal('payment')->default(0)->comment('销售金额');
            $table->decimal('price')->default(0)->comment('单价(商品标价)');
            $table->decimal('receivable')->default(0)->comment('应收');
            $table->text('remark')->default('')->comment('明细备注');
            $table->integer('size')->default(0)->comment('数量');
            $table->string('sku_code')->default('')->comment('规格编码');
            $table->string('sn_value')->default('')->comment('序列号，此字段以不返回数据，请使接口——/erp/sn/querysnbybillcode查询单据中的序列号');
            $table->integer('status')->default(0)->comment('状态:1:等待付款 2:等待发货 ,部分发货 3:已完成 4:已关闭 5: 等待确认 6:已签收 0: 未建交易');
            $table->string('sub_order_no')->default('')->comment('子订单号');
            $table->decimal('tax_rate')->default(0)->comment('开票商品税率');
            $table->string('tid_snapshot')->default('')->comment('原始编号');
            $table->string('tp_oid')->default('')->comment('线上明细ID');
            $table->string('tp_tid')->default('')->comment('线上单号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hupun_trades_orders');
    }
}
