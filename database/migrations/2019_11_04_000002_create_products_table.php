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
        $precisionInt = config('product.precision.int');    // 整型精度(位)
        $precisionDecimal = config('product.precision.decimal');    // 小数精度(位)
        // 商品表
        Schema::create('products', function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->comment('分类');
            $table->string('title')->comment('产品标题');
            $table->json('main_pics')->comment('产品主图');
            $table->unsignedInteger('total_numbers')->comment('库存总数量(skus总和/库存总和)');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('一口价');
            $table->boolean('on_sale')->comment('是否上架');
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