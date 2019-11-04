<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSkusTable extends Migration
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

        Schema::create('product_skus', function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('商品');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('金额');
            $table->unsignedInteger('stock')->comment('库存');
            $table->json('specs')->comment('属性规格 对应sku');
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
        Schema::dropIfExists('product_skus');
    }
}