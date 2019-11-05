<?php

namespace Gtd\Product;

use Illuminate\Database\Eloquent\Model;

class AttrKey extends Model
{
    protected $guarded = ['id'];

    public function attrValues()
    {
        return $this->hasMany(AttrValue::class);
    }
}