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
        $precisionInt = config('product_sku.precision.int');    // 整型精度(位)
        $precisionDecimal = config('product_sku.precision.decimal');    // 小数精度(位)

        Schema::create('product_skus', function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('所属商品');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('金额');
            $table->unsignedInteger('stock_count')->comment('库存');
            $table->json('specs')->comment('属性规格');
//            $table->json('extra')->comment('额外内容');
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