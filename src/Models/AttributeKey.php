<?php

namespace Gtd\Product\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeKey extends Model
{
    protected $guarded = ['id'];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }
}