<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商品分类表
        Schema::create(config('product_sku.table_names.category'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父级分类');
            $table->string('name')->comment('分类名称');
            $table->unsignedInteger('order')->nullable()->comment('排序');
            $table->boolean('customizable_attr')->comment('是否能够自定义属性键值对');
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
        Schema::dropIfExists(config('product_sku.table_names.category'));
    }
}