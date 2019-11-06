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

        Schema::create(config('product_sku.table_names.sku'), function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('所属商品');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('金额');
            $table->unsignedInteger('stock_count')->comment('库存');
            $table->json('specs')->comment('属性规格');
            $specs = [
                'attr1' => 'xx',
                'attr2' => 'xx',
            ];
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
        Schema::dropIfExists(config('product_sku.table_names.sku'));
    }
}