<?php

namespace Gtd\Sku\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model implements \Gtd\Sku\Contracts\Option
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_sku' => 'bool'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('sku.table_names.options'));
    }

    public static function createForSku(array $attributes): self
    {
        $attributes['is_sku'] = true;
        return static::create($attributes);
    }

    public static function findByName(string $name): self
    {
        return static::whereName($name)->first();
    }
}