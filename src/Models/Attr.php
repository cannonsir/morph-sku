<?php

namespace Gtd\Sku\Models;

use Gtd\Sku\Contracts\AttrContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attr extends Model implements AttrContract
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('sku.table_names.attrs'));
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(config('sku.models.option'));
    }

    public function producible(): MorphTo
    {
        return $this->morphTo(config('sku.morph_name'));
    }

    public function skus(): BelongsToMany
    {
        return $this->belongsToMany(config('sku.models.sku'));
    }
}