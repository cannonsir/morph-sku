<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTables extends Migration
{
    public function up()
    {
        // 分类
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id');
            $table->string('name');
        });

        // 产品
        Schema::create('products', function (Blueprint $table) {
            // 标题 主图 详情 属性
            $table->bigIncrements('id');
        });

        // sku
        Schema::create('skus', function (Blueprint $table) {
            $table->decimal('amount');
            $table->unsignedInteger('stock');
        });

        // sku 分类 -> 一对多 属性 更细致 同样的颜色属性可能在不同分类下有不同的属性值
        // 属性还有二次属性分类 展示
        Schema::create('attributes', function (Blueprint $table) {
            // 是否可搜索
            //
        });

        // sku
        Schema::create('attribute_values', function (Blueprint $table) {

        });

        // sku需要配合搜索

        // sku - 属性值 多对多 可以通过属性值查询所属属性
        Schema::create('attribute_value_sku', function (Blueprint $table) {

        });

        // 只需要确认属性值即可获取属性，自定义属性值呢?
        // - 颜色 : 黑色 白色
        // - 尺寸 : S L
    }

    public function down()
    {
        //
    }
}