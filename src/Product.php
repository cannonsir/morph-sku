<?php

namespace Gtd\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'main_pics' => 'array',
        'on_sale' => 'bool'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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