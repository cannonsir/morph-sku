<?php

return [
    // 金额字段精度配置
    'precision' => [
        'int' => 10, // 整型部分精度(位)
        'decimal' => 2  // 小数部分精度(位)
    ],

    // 允许的属性值类型列表，需继承自 \Gtd\Product\SkuAttrAccepts\Accept::class
    'accepts' => [
        Gtd\Product\SkuAttrAccepts\Radio::class,
        Gtd\Product\SkuAttrAccepts\Number::class,
        Gtd\Product\SkuAttrAccepts\Str::class,
        Gtd\Product\SkuAttrAccepts\CheckBox::class,
        Gtd\Product\SkuAttrAccepts\CustomizableCheckBox::class,
        Gtd\Product\SkuAttrAccepts\Date::class,
        Gtd\Product\SkuAttrAccepts\Time::class,
        Gtd\Product\SkuAttrAccepts\DateTime::class,
    ],

    // 表名
    'table_names' => [
        // 商品分类
        'category' => 'product_categories',
        // 商品
        'product' => 'products',
        // 商品sku
        'sku' => 'product_skus',
        // 商品sku属性键
        'sku_attr_key' => 'product_sku_attr_keys',
        // 商品sku属性值
        'sku_attr_val' => 'product_sku_attr_vals'
    ]
];
