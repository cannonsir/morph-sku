<?php

return [
    // 金额字段精度配置
    'precision' => [
        'int' => 10, // 整型部分精度(位)
        'decimal' => 2  // 小数部分精度(位)
    ],

    // 表名
    'table_names' => [
        // 商品分类
        'categories' => 'categories',

        // 商品
        'products' => 'products',

        // 商品sku
        'skus' => 'skus',

        // 商品分类sku属性键
        'attributes' => 'attributes',

        // 属性-分类中间表
        'attribute_category' => 'attribute_category',

        // 商品分类sku属性预设值
        'attribute_values' => 'attribute_values'
    ],

    // 允许的属性值类型列表，必须实现 \Gtd\Product\Contracts\SkuAttrAccept interface
    'attribute_value_accepts' => [
        'radio' => Gtd\Product\SkuAttrAccepts\Radio::class,
        'number' => Gtd\Product\SkuAttrAccepts\Number::class,
        'str' => Gtd\Product\SkuAttrAccepts\Str::class,
        'check_box' => Gtd\Product\SkuAttrAccepts\CheckBox::class,
        'customizable_check_box' => Gtd\Product\SkuAttrAccepts\CustomizableCheckBox::class,
        'date' => Gtd\Product\SkuAttrAccepts\Date::class,
        'time' => Gtd\Product\SkuAttrAccepts\Time::class,
        'datetime' => Gtd\Product\SkuAttrAccepts\DateTime::class,
    ],

    'attribute_types' => [
        'category',
        'sku'
    ],

    // 商品与属性之间的隐士关联 如何体现
    // 要通过属性键值能够搜索到商品
    'product_attribute_map' => [
        // 没有id
        'attribute_key' => 'attribute_value',
        'attribute_key2' => 'attribute_value',
        'attribute_key3' => 'attribute_value',
    ]
];
