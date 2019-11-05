<?php

namespace Gtd\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttrValue extends Model
{
    protected $guarded = ['id'];

    public function attr(): BelongsTo
    {
        return $this->belongsTo(AttrKey::class);
    }
}