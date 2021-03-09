<?php

namespace Connonsir\MorphSku\Traits;

use Connonsir\MorphSku\Contracts\AttrContract;
use Connonsir\MorphSku\Contracts\OptionContract;
use Connonsir\MorphSku\Models\Sku;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait HasSku
{
    /**
     * 属性值
     *
     * @return MorphMany
     */
    public function attrs(): MorphMany
    {
        return $this->morphMany(config('morph-sku.models.Attr'), config('morph-sku.morph_name'));
    }

    /**
     * SKU
     *
     * @return MorphMany
     */
    public function skus(): MorphMany
    {
        // sku列表，及每条sku对应属性键值列表，价格，库存
        return $this->morphMany(config('morph-sku.models.Sku'), config('morph-sku.morph_name'));
    }

    /**
     * 同步属性值
     *
     * @param OptionContract|integer|string $option
     * @param mixed ...$values
     */
    public function syncAttrValues($option, ...$values)
    {
        $option_id = $this->findOrCreateOption($option)->getKey();
        $values = Arr::flatten($values);

        // 已存在指定values的属性值
        $has = $this->attrs()
            ->where('option_id', $option_id)
            ->whereIn('value', $values)
            ->get();

        $attrClass = config('morph-sku.models.Attr');
        $attrModelKeyName = (new $attrClass)->getKeyName();

        $newAttrs = [];
        foreach ($values as $value) {
            // 不存在的新值写入新增数组
            if (is_null($has->firstWhere('value', $value))) {
                $newAttrs[] = new $attrClass(compact('option_id', 'value'));
            }
        }

        DB::transaction(function () use ($option_id, $attrModelKeyName, $has, $newAttrs) {
            // 删除其它
            $this->attrs()
                ->where('option_id', $option_id)
                ->whereNotIn($attrModelKeyName, $has->pluck($attrModelKeyName))
                ->delete();

            // 保存新增
            $this->attrs()->saveMany($newAttrs);
        });
    }

    /**
     * 移除商品的某选项的对应属性值
     *
     * @param OptionContract|integer|string $option
     * @return mixed
     */
    public function removeAttrValues($option)
    {
        $option = $this->findOrCreateOption($option);

        return $this->attrs()->where('option_id', $option->getKey())->delete();
    }

    /**
     * 添加属性值
     *
     * @param OptionContract|integer|string $option
     * @param mixed ...$values
     * @return iterable
     */
    public function addAttrValues($option, ...$values)
    {
        if (empty($values)) {
            throw new \InvalidArgumentException('Values cannot be empty');
        }

        $option = $this->findOrCreateOption($option);

        $values = collect($values)->flatten()->map(function ($value) use ($option) {
            $attribute_class = config('morph-sku.models.Attr');
            $option_id = $option->getKey();
            return new $attribute_class(compact('option_id', 'value'));
        });

        return $this->attrs()->saveMany($values);
    }

    /**
     * 通过属性值组合更新或创建sku
     *
     * @param array $position
     * @param array $payload
     * @return Sku|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public function syncSkuWithAttrs(array $position, array $payload)
    {
        // 验证属性值不能为空
        if (empty($position)) {
            throw new \InvalidArgumentException('Positioning is empty');
        }

        // 解析属性值为属性值id数组
        $position = array_map(function ($point) {
            if (is_numeric($point) || is_string($point)) {
                return $point;
            }

            if ($point instanceof AttrContract) {
                return $point->getKey();
            }

            throw new \InvalidArgumentException("Invalid attribute value");
        }, $position);

        // 验证属性值重复
        if (count($position) !== count(array_unique($position))) {
            throw new \InvalidArgumentException('Duplicate attribute value');
        }

        $attrModel = config('morph-sku.models.Attr');
        // 各属性值的选项id
        $option_ids = $this->attrs()
            ->whereIn((new $attrModel)->getKeyName(), $position)
            ->pluck('option_id')
            ->toArray();

        // 验证此商品下存在此属性值
        if (count($position) !== count($option_ids)) {
            throw new \InvalidArgumentException('The attribute value under this item does not exist');
        }

        // 验证同一SKU下的属性值的选项名称不重复
        if (count($option_ids) !== count(array_unique($option_ids))) {
            throw  new \InvalidArgumentException('Duplicate options for attribute values ​​under the same SKU');
        }

        $sku = Sku::findByPosition($position);

        // 如果SKU存在则更新，否则创建
        if (is_null($sku)) {
            $sku = $this->skus()->create($payload);
            $sku->attrs()->sync($position);
        } else {
            $sku->update($payload);
        }

        return $sku;
    }

    /**
     * 查询选项实例
     *
     * @param OptionContract|integer|string $option
     * @return mixed
     */
    protected function findOrCreateOption($option): OptionContract
    {
        $optionModel = config('morph-sku.models.Option');

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
}