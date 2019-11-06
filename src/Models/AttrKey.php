<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model;

class AttrKey extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('product_sku.table_names.sku_attr_key'));
    }

    public function attrVals()
    {
        return $this->hasMany(AttrValue::class);
    }
}