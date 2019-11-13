<?php

return [
    'models' => [
        // 商品sku
        'sku' => \Gtd\MorphSku\Models\Sku::class,

        // 选项
        'option' => \Gtd\MorphSku\Models\Option::class,

        // 属性
        'attr' => \Gtd\MorphSku\Models\Attr::class,
    ],

    // 表名
    'table_names' => [
        // 商品sku
        'skus' => 'skus',

        // 选项属性
        'options' => 'options',

        // 属性键值
        'attrs' => 'attrs',

        // 属性值与sku 中间表
        'attr_sku' => 'attr_sku',
    ],

    'morph_name' => 'producible'
];
