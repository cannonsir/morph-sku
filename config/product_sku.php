<?php

return [
    // 金额精度配置
    'precision' => [
        'int' => 10, // 整型部分精度(位)
        'decimal' => 2  // 小数部分精度(位)
    ],

    // TODO sku属性字段要不要做验证层呢，如何解耦
    // ['单选', '数字', '字符串', '多选', '多选+自定义输入', '日期', '时间', '日期时间']
    // 属性字段值类型
    'accept' => [
        'radio' => '',
        'number',
        'string',
        'checkbox',
        'checkbox+',
        'date',
        'time',
        'datetime',
    ],

    'field_types_rules' => [

    ]
];
