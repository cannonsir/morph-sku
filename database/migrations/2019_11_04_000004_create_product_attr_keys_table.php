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
        Schema::create('product_attr_keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->comment('所属分类');
            $table->string('name')->comment('属性名称');
            $table->boolean('required')->comment('是否必填');
            $table->enum('type', ['category', 'sku'])->comment('类目属性/sku属性');
            // 可用于验证值规则,前端也好渲染输入组件
            $table->enum('accept', ['单选', '数字', '字符串', '多选', '多选+自定义输入', '日期', '时间', '日期时间'])->comment('字段值类型');
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
        Schema::dropIfExists('product_attr_keys');
    }
}