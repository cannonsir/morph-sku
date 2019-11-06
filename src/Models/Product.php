<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model implements \Gtd\Product\Contracts\Product
{
    protected $guarded = ['id'];

    protected $casts = [
        'pics' => 'array',
        'on_sale' => 'bool',
        'total_stock_count' => 'int'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('product_sku.table_names.product'));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }

    /*
     * 上架
     */
    public function upperShelf()
    {
        $this->on_sale = true;
        $this->save();
    }

    /*
     * 下架
     */
    public function lowerShelf()
    {
        $this->on_sale = false;
        $this->save();
    }
}