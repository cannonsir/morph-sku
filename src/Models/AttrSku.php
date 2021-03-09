<?php

namespace Connonsir\MorphSku\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AttrSku extends Pivot
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('morph-sku.table_names.attr_sku'));
    }
}