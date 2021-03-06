<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHupunTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hupun_trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rawid')->comment('原始数据对应id');
            $table->string('address')->default('')->comment('地址');
            $table->string('buyer')->default('')->comment('买家昵称');
            $table->string('buyer_account')->default('')->comment('账号');
            $table->string('buyer_mobile')->default('');
            $table->text('buyer_msg')->nullable()->comment('买家留言');
            $table->string('channel_name')->default('')->comment('买家渠道昵称');
            $table->string('city')->default('')->comment('市');
            $table->string('country')->default('')->comment('国家');
            $table->dateTime('create_time')->nullable()->comment('创建时间');
            $table->string('currency_code')->default('')->comment('原始货币种类');
            $table->decimal('currency_sum')->default(0)->comment('原始货币金额');
            $table->decimal('discount_fee')->default(0)->comment('优惠金额');
            $table->string('district')->default('')->comment('区');
            $table->dateTime('end_time')->nullable()->default(null)->comment('完成时间：交易结束或交易成功的时间');
            $table->boolean('exchange_trade')->default(false)->comment('是否售后订单');
            $table->string('express_code')->default('')->comment('快递单号');
            $table->integer('flag')->default(0)->comment('旗子颜色0:无1：红2：黄3：绿4：蓝5：粉');
            $table->integer('has_refund')->default(0)->comment('是否有退款');
            $table->string('identity_name')->default('')->comment('身份证名称');
            $table->string('identity_num')->default('')->comment('身份信息');
            $table->boolean('is_exception_trade')->default(false)->comment('是否异常订单');
            $table->boolean('is_pay')->default(false)->comment('是否已付款');
            $table->boolean('is_small_trade')->default(false)->comment('是否jit小单');
            $table->string('jz_install_code')->default('')->comment('安装服务商编码-- 淘系家装类订单字段');
            $table->string('jz_install_name')->default('')->comment('安装服务商名称-- 淘系家装类订单字段');
            $table->string('jz_server_code')->default('')->comment('物流服务商编码-- 淘系家装类订单字段');
            $table->string('jz_server_name')->default('')->comment('物流服务商名称-- 淘系家装类订单字段');
            $table->string('logistic_code')->default('')->comment('万里牛ERP快递公司代码，用户自定义代码');
            $table->string('logistic_name')->default('')->comment('万里牛ERP快递公司名称');
            $table->string('mark')->default('')->comment('订单标记');
            $table->dateTime('modify_time')->nullable()->comment('修改时间');
            $table->longText('oln_order_list')->nullable()->comment('明细线上单号集合--json');
            $table->integer('oln_status')->default(0)->comment('线上状态:1:等待付款 2:等待发货 ,部分发货 3:已完成 4:已关闭 5: 等待确认 6:已签收 0: 未建交易');
            $table->decimal('paid_fee')->default(0)->comment('实际支付金额');
            $table->string('pay_no')->default('')->comment('外部支付单号');
            $table->dateTime('pay_time')->nullable()->comment('付款时间');
            $table->string('pay_type')->default('')->comment('支付类型');
            $table->string('phone')->default('')->comment('手机号，手机号为空的时候返回电话');
            $table->decimal('post_cost')->default(0)->comment('快递成本,数值可能会变动(如果设置了运费计算规则，订单审核后会根据商品估量和运费规则计算运费成本,称重后根据实际重量重新计算！页面也能直接修改)');
            $table->decimal('post_fee')->default(0)->comment('邮费');
            $table->text('print_remark')->nullable()->comment('打印备注');
            $table->dateTime('print_time')->nullable()->comment('打单时间');
            $table->integer('process_status')->default(0)->comment('万里牛单据处理状态: -3:分销商审核 -2:到账管理 -1:未付款 0:审核 1:打单配货 2:验货 3:称重 4:待发货 5：财审 8:已发货 9:成功 10:关闭 11:异常结束 12:异常处理 13:外部系统配货中 14:预售 15:打包');
            $table->string('province')->default('')->comment('省');
            $table->string('receiver')->default('')->comment('收件人');
            $table->text('remark')->nullable()->comment('备注');
            $table->string('sale_man')->default('')->comment('业务员');
            $table->string('seller_msg')->default('')->comment('卖家留言');
            $table->dateTime('send_time')->nullable()->comment('发货时间');
            $table->decimal('service_fee')->default(0)->comment('服务费');
            $table->string('shop_id')->default('');
            $table->string('shop_name')->default('')->comment('店铺名称(页面上显示)');
            $table->string('shop_nick')->default('')->comment('店铺昵称(店铺唯一)');
            $table->integer('shop_type')->default(0)->comment('平台枚举类型');
            $table->string('source_platform')->default('')->comment('订单来源平台');
            $table->boolean('split_trade')->default(false)->comment('是否拆分订单');
            $table->integer('status')->default(0)->comment('状态：1：处理中 2：发货 3：完成 4: 关闭 5:其他');
            $table->string('storage_code')->default('')->comment('仓库编码');
            $table->string('storage_name')->default('')->comment('仓库名称');
            $table->decimal('sum_sale')->default(0)->comment('总金额,包含优惠');
            $table->string('tags')->default('');
            $table->string('tel')->default('')->comment('电话');
            $table->integer('tp_logistics_type')->default(-1)->comment('0 快递 1 EMS 2 平邮 11 虚拟商品 121 自提 122 商家自送 125 同城限时达 -1 其它');
            $table->longText('tp_tid')->nullable()->comment('线上单号,如果是线下订单，则是万里牛的单号，合单情况下会将单号合并，使用|做分隔符');
            $table->longText('trade_extend')->nullable()->comment('定制扩展字段--json');
            $table->string('trade_no')->default('')->comment('订单编码');
            $table->integer('trade_type')->default(1)->comment('订单类型 :1:普通线上订单 5：货到付款 6：分销 7：团购类型 9：天猫国际物流订单类型 50：普通线下订单 51：售后订单（一般换货创建的订单）');
            $table->string('uid')->default('');
            $table->decimal('volume')->default(0)->comment('体积 单位：立方米');
            $table->decimal('weight')->default(0)->comment('重量 单位：千克');
            $table->string('zip')->default('')->comment('邮编');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hupun_trades');
    }
}
