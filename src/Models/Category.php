<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'customizable_attr' => 'bool'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('product_sku.table_names.categories'));
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}