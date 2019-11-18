<?php

namespace Gtd\MorphSku\Models;

use function Couchbase\defaultDecoder;
use Gtd\MorphSku\Contracts\AttrContract;
use Gtd\MorphSku\Contracts\SkuContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

class Sku extends Model implements SkuContract
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('morph-sku.table_names.skus'));
    }

    public function producible(): MorphTo
    {
        return $this->morphTo(config('morph-sku.morph_name'));
    }

    public function attrs(): BelongsToMany
    {
        return $this->belongsToMany(
            config('morph-sku.models.Attr'),
            config('morph-sku.table_names.attr_sku')
        )->using(config('morph-sku.models.AttrSku'));
    }

    public function syncAttrs(...$attrs)
    {
        $attrs = $this->parseEqualProductAttrs(...$attrs);

        $this->attrs()->sync($attrs->map->getKey());
    }

    public function assignAttrs(...$attrs)
    {
        $attrs = $this->parseEqualProductAttrs(...$attrs);

        $this->attrs()->attach($attrs->map->getKey());
    }

    public function removeAttrs(...$attrs)
    {
        $attrs = $this->parseEqualProductAttrs(...$attrs);

        $attrs->isEmpty()
            ? $this->attrs()->detach()
            : $this->attrs()->detach($attrs);
    }

    public static function findByPosition(...$position)
    {
        $position = collect($position)
            ->flatten()
            ->map(function ($val) {
                if (is_numeric($val) || is_string($val)) {
                    return $val;
                }

                if ($val instanceof AttrContract) {
                    return $val->getKey();
                }

                throw new \InvalidArgumentException('Invalid attribute value');
            })
            ->unique()
            ->toArray();

        return static::whereHas('attrs', function (Builder $builder) use ($position) {
            $attr_sku_table = config('morph-sku.table_names.attr_sku');
            $builder->whereRaw(sprintf('%s.attr_id IN (%s)', $attr_sku_table, implode(',', $position)));
        }, '=', count($position))->first();
    }

    /**
     * 解析同产品下属性值实例
     *
     * @param mixed ...$attrs
     * @return \Illuminate\Support\Collection
     */
    protected function parseEqualProductAttrs(...$attrs): Collection
    {
        return collect($attrs)->flatten()->map(function ($attr) {
            $attr = $this->getStoredAttr($attr);

            if (!$this->attrIsFormEqualProduct($attr)) {
                throw new \InvalidArgumentException('无效属性值');
            }

            return $attr;
        });
    }

    /**
     * 获取属性值实例
     *
     * @param $attr
     * @return AttrContract
     */
    protected function getStoredAttr($attr): AttrContract
    {
        if (is_numeric($attr)) {
            $attrModel = config('morph-sku.models.Attr');
            $attr = $attrModel::findOrFail($attr);
        }

        if (!$attr instanceof AttrContract) {
            throw new \InvalidArgumentException('无效属性值');
        }

        return $attr;
    }

    /**
     * 判断属性值是否与sku来自同一产品
     *
     * @param AttrContract $attr
     * @return mixed
     */
    protected function attrIsFormEqualProduct(AttrContract $attr): bool
    {
        return $attr->producible->is($this->producible);
    }
}