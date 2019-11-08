<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTables extends Migration
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
        $tableNames = config('product_sku.table_names');

        // 商品分类表
        Schema::create($tableNames['categories'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->index()->nullable()->comment('父级分类');
            $table->string('name')->comment('分类名称');
            $table->unsignedInteger('order')->default(0)->comment('排序');
            $table->boolean('customizable_attr')->default(0)->comment('是否能够自定义属性键值对');
            $table->timestamps();
            $table->unique(['parent_id', 'name']);
        });

        // 商品表
        Schema::create($tableNames['products'], function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->comment('分类');
            $table->string('title')->comment('产品标题');
            $table->string('main_pic')->comment('主图');
            $table->unsignedInteger('stock_count')->comment('库存总数量(skus总和/库存总和) 并不冗余，可能没有sku');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('一口价');
            $table->json('pics')->nullable()->comment('产品主图列表');
            $table->json('attribute_value')->nullable()->comment('属性键值对');
            $table->json('custom_attribute_value')->nullable()->comment('自定义属性键值对');
            $table->boolean('on_sale')->comment('是否上架');
            $table->timestamps();
        });

        // skus表
        Schema::create($tableNames['skus'], function (Blueprint $table) use ($precisionInt, $precisionDecimal) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('所属商品');
            $table->unsignedDecimal('amount', $precisionInt, $precisionDecimal)->comment('金额');
            $table->unsignedInteger('stock_count')->comment('库存');
            $table->json('specs')->comment('属性规格');
            $table->timestamps();
        });

        // 属性表
        Schema::create($tableNames['attributes'], function (Blueprint $table) {
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
            $table->unique(['category_id', 'name']);
        });

        // 属性值表
        Schema::create($tableNames['attribute_values'], function (Blueprint $table) use ($tableNames) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attribute_id')->comment('所属属性键');
            $table->string('type')->comment('值类型');
            $table->text('value')->comment('属性值');
            $table->timestamps();
            $table->foreign('attribute_id')
                ->references('id')->on($tableNames['attributes'])
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('product_sku.table_names');

        foreach ($tableNames as $table) {
            Schema::dropIfExists($table);
        }
    }
}