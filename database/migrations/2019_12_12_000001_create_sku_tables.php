<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuTables extends Migration
{
    public function up()
    {
        Schema::create(config('sku.table_names.skus'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('producible');
            $table->decimal('amount');
            $table->unsignedInteger('stock');
            $table->timestamps();
        });

        Schema::create(config('sku.table_names.options'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->unique('name');
        });

        Schema::create(config('sku.table_names.attrs'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('producible');
            $table->unsignedBigInteger('option_id')->index();
            $table->string('value');
            $table->timestamps();
        });

        Schema::create(config('sku.table_names.attr_sku'), function (Blueprint $table) {
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('attr_id');
            $table->unique(['sku_id', 'attr_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('sku.table_names.skus'));
        Schema::dropIfExists(config('sku.table_names.options'));
        Schema::dropIfExists(config('sku.table_names.attrs'));
    }
}