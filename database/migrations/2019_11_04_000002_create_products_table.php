<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商品表
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
//            $table->unsignedBigInteger('category_id')->comment('分类');
            $table->string('delivery_address_id')->comment('发货地址');

            $table->string('no')->comment('编码');
            $table->string('title')->comment('产品标题');
            $table->json('main_pics')->comment('产品主图');
//            $table->enum('payment_method', ['normal'])->comment('支付方式');
//            $table->enum('inventory_count_method', ['order_placed', 'paid'])->comment('库存计数方式:拍下减库存/付款减库存');
//            $table->string('custom_code')->comment('自定义编码');
//            $table->string('custom_bar_code')->comment('自定义条形码');
            $table->unsignedInteger('total_numbers')->comment('库存总数量(skus总和/库存总和)');
            $table->unsignedDecimal('price', 11, 2)->comment('一口价');
//            $table->unsignedBigInteger('sales_volume')->comment('销量');
//            $table->json('after_service')->comment('售后服务');
            $table->unsignedTinyInteger('status')->comment('状态 1未上架 2已上架');

            $table->timestamp('shelf_at')->comment('上架时间');
            $table->timestamps();
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
}