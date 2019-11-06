<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttrValsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 属性值表
        Schema::create(config('product_sku.table_names.product_sku_attr_vals'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attribute_key_id')->comment('所属属性键');
            $table->text('value')->comment('属性值');
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
        Schema::dropIfExists(config('product_sku.table_names.product_sku_attr_vals'));
    }
}