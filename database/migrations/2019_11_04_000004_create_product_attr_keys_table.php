<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttrKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 属性表
        Schema::create(config('product_sku.table_names.sku_attr_key'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->comment('所属分类');
            $table->string('name')->comment('属性名称');
            $table->boolean('required')->comment('是否必填');
            $table->enum('type', ['category', 'sku'])->comment('类目属性/sku属性');
            $table->string('accept')->comment('字段值类型');
            $table->text('default')->nullable()->comment('默认值');
            $table->string('unit')->nullable()->comment('单位');
            $table->string('tips')->nullable()->comment('提示');
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
        Schema::dropIfExists(config('product_sku.table_names.sku_attr_key'));
    }
}