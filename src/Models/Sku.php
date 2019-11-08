<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sku extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'specs' => 'array',
        'stock_count' => 'int'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('product_sku.table_names.skus'));
    }

    public static function create(array $attributes = [])
    {
        // TODO 创建sku时验证字段类型是否正确及值是否合法
        if (isset($attributes['accept'])) {

        }

        return static::query()->create($attributes);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function accepts()
    {

    }
}