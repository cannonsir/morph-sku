<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{
    protected $guarded = ['id'];

    public function attributeKey(): BelongsTo
    {
        return $this->belongsTo(AttributeKey::class);
    }
}