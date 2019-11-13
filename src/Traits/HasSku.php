<?php

namespace Gtd\MorphSku\Traits;

use Gtd\MorphSku\Contracts\AttrContract;
use Gtd\MorphSku\Contracts\OptionContract;
use Gtd\MorphSku\Contracts\SkuContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

trait HasSku
{
    /**
     * 获取sku
     *
     * @return MorphMany
     */
    public function skus(): MorphMany
    {
        // sku列表，及每条sku对应属性键值列表，价格，库存
        return $this->morphMany(config('morph-sku.models.sku'), config('sku.morph_name'));
    }

    /**
     * 获取属性
     *
     * @return MorphMany
     */
    public function attrs(): MorphMany
    {
        return $this->morphMany(config('morph-sku.models.attr'), config('morph-sku.morph_name'));
    }

    /**
     * 添加属性值
     *
     * @param Option $option
     * @param mixed ...$values
     * @return iterable
     */
    public function addAttrValues($option, ...$values)
    {
        $option = $this->findOrCreateOption($option);

        $values = collect($values)->flatten()->map(function ($value) use ($option) {
            $attribute_class = config('morph-sku.models.attr');
            $option_id = $option->getKey();
            return new $attribute_class(compact('option_id', 'value'));
        });

        if ($values->isEmpty()) {
            throw new \InvalidArgumentException('Values cannot be empty');
        }

        return $this->attrs()->saveMany($values);
    }

    /**
     * 同步属性值
     *
     * @param Option $option
     * @param mixed ...$values
     * @return iterable
     * @throws \Exception
     */
    public function syncAttrValues($option, ...$values)
    {
        DB::transaction(function () use (&$res, $option, $values) {
            $option = $this->findOrCreateOption($option);

            $this->removeAttrValues($option);

            $res = $this->addAttrValues($option, ...$values);
        });

        return $res;
    }

    /**
     * 移除某选项及属性值
     *
     * @param $option
     * @return mixed
     */
    public function removeAttrValues($option)
    {
        $option = $this->findOrCreateOption($option);

        return $this->attrs()->where($option->getKeyName(), $option->getKey())->delete();
    }

    /**
     * 通过属性值新增sku
     *
     * @param array $attrs
     * @param array $payload
     * @return SkuContract
     */
    public function addSkuWithAttrs(array $attrs, array $payload): SkuContract
    {
        $attr_ids = $this->parseAndVerifyPosition($attrs);

        $sku = $this->skus()->create($payload);
        $sku->attrs()->attach($attr_ids);

        return $sku;
    }

    /**
     * 解析属性值组合为id，并验证属性值组合存在，重复，合法性
     *
     * @param array $position
     * @return array
     */
    protected function parseAndVerifyPosition(array $position): array
    {
        $attr_ids = array_map(function ($val) {
            if ($val instanceof AttrContract) {
                $val = $val->getKey();
            }

            if (!is_string($val) && !is_numeric($val)) {
                throw new \InvalidArgumentException('参数非法');
            }

            return $val;
        }, $position);

        if (count($attr_ids) !== count(array_unique($attr_ids))) {
            throw new \InvalidArgumentException('属性值重复');
        }

        $attrModel = config('morph-sku.models.attr');
        $option_ids = $this->attrs()->whereIn((new $attrModel)->getKeyName(), $attr_ids)->pluck('option_id')->toArray();

        // 验证产品属性值存在
        if (count($attr_ids) !== count($option_ids)) {
            throw new \InvalidArgumentException('产品属性值不存在');
        }

        if (count($option_ids) !== count(array_unique($option_ids))) {
            throw  new \InvalidArgumentException('同选项值重复');
        }

        // 该组合是否已经存在
        $exists = !$this->skus()->select('id')->with('attrs:id')->get()->every(function ($sku) use ($attr_ids) {
            $position = $sku->attrs->pluck('id')->toArray();
            sort($position);
            sort($attr_ids);
            return $position !== $attr_ids;
        });

        if ($exists) {
            throw new \InvalidArgumentException('属性值组合已存在');
        }

        return $attr_ids;
    }

    /**
     * 查询选项实例
     *
     * @param $option
     * @return mixed
     */
    protected function findOrCreateOption($option): OptionContract
    {
        $optionModel = config('morph-sku.models.option');

        if (is_numeric($option)) {
            $option = $optionModel::findOrFail($option);
        }

        if (is_string($option)) {
            $option = $optionModel::firstOrCreate(['name' => $option]);
        }

        if (! ($option instanceof OptionContract)) {
            throw (new ModelNotFoundException)->setModel($optionModel);
        }

        return $option;
    }

    /**
     * 访问器：获取sku矩阵
     */
    public function getSkuMatrixAttribute()
    {
        return $this->skus()
            ->with('attrs.option:id,name', 'attrs:id,option_id,value')
            ->get()
            ->transform(function (SkuContract $sku) {
                $sku = $sku->toArray();
                $sku['attrs'] = array_map(function ($attr) {
                    return [
                        'id' => $attr['id'],
                        'value' => $attr['value'],
                        'option_id' => $attr['option_id'],
                        'option' => $attr['option']['name'],
                    ];
                }, $sku['attrs']);

                return $sku;
            })
            ->toArray();
    }
}