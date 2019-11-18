<?php

namespace Gtd\MorphSku\Models;

use Gtd\MorphSku\Contracts\AttrContract;
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

        $this->setTable(config('morph-sku.table_names.attrs'));
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(config('morph-sku.models.Option'));
    }

    public function producible(): MorphTo
    {
        return $this->morphTo(config('morph-sku.morph_name'));
    }

    public function skus(): BelongsToMany
    {
        return $this->belongsToMany(
            config('morph-sku.models.Sku'),
            config('morph-sku.table_names.attr_sku')
        )->using(config('morph-sku.models.AttrSku'));
    }
}