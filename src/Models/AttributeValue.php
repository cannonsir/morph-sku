<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model
    ;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('product_sku.table_names.attribute_values'));
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function (self $self) {
            $acceptClass = config('product_sku.attribute_value_accepts')[$self->type];
            $self->value = $acceptClass::setter($self->value);
        });
    }

    public static function create(array $attributes = []) {
        $attribute = Attribute::query()->findOrFail($attributes['attribute_id']);

        $attribute->presetVerify($attributes['value']);

        $attributes['type'] = $attribute->accept;

        return static::query()->create($attributes);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /*
     * 访问器
     */
    public function getValueAttribute($value)
    {
        $acceptClass = config('product_sku.attribute_value_accepts')[$this->type];

        return $acceptClass::getter($value);
    }
}