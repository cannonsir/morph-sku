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
        $precisionInt = config('product_sku.precision.int');    // 整型精度(位)
        $precisionDecimal = config('product_sku.precision.decimal');    // 小数精度(位)
        // 商品表
        Schema::create(config('product_sku.table_names.product'), function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->comment('分类');
            $table->string('title')->comment('产品标题');
            $table->string('main_pic')->comment('主图');
            $table->json('pics')->comment('产品主图列表');
            $table->unsignedInteger('total_stock_count')->comment('库存总数量(skus总和/库存总和) 并不冗余，可能没有sku');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('一口价');
            $table->json('custom_attr')->nullable()->comment('自定义属性');
            $table->boolean('on_sale')->comment('是否上架');
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
        Schema::dropIfExists(config('product_sku.table_names.product'));
    }
}