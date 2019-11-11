<?php

namespace Gtd\Sku\Traits;

use Gtd\Sku\Contracts\Attr;
use Gtd\Sku\Contracts\Option;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

trait HasSku
{
    // sku关联
    public function skus(): MorphMany
    {
        // sku列表，及每条sku对应属性键值列表，价格，库存
        return $this->morphMany(config('sku.models.sku'), config('sku.morph_name'));
    }

    // 属性键值对关联
    public function attrs(): MorphMany
    {
        return $this->morphMany(config('sku.models.attr'), config('sku.morph_name'));
    }

    /**
     * 批量添加属性值
     *
     * @param Option $option
     * @param mixed ...$values
     * @return iterable
     */
    public function addAttrValues($option, ...$values)
    {
        if (is_string($option) || is_numeric($option)) {
            $option = config('sku.models.option')::firstOrCreate(['name' => $option]);
        }

        if (! ($option instanceof Option)) {
            throw new \InvalidArgumentException('Invalid option');
        }

        $values = collect($values)->flatten()->map(function ($value) use ($option) {
            $attribute_class = config('sku.models.attr');
            $option_id = $option->getKey();
            return new $attribute_class(compact('option_id', 'value'));
        });

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
    public function syncAttrValues(Option $option, ...$values)
    {
        DB::beginTransaction();

        try {
            $this->removeAttrs($option);

            $res = $this->addAttrValues($option, ...$values);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

        return $res;
    }

    /**
     * 批量移除属性及所有值
     *
     * @param $options
     * @return mixed
     */
    public function removeAttrs($options)
    {
        $option_ids = collect($options)
            ->filter(function ($option) {
                return $option instanceof Option;
            })
            ->map(function ($option) {
                return $option->getKey();
            });

        $attrClass = config('sku.models.attr');
        $column = (new $attrClass)->getKeyName();

        return $this->attrs()->whereIn($column, $option_ids)->delete();
    }

    /**
     * 更新sku列表及载荷
     * TODO 可用性及参数优化
     * @param \Closure $closure
     */
    public function updateSkuList(\Closure $closure)
    {
        // sku列表
        $list = [];

        // 执行闭包，获取新sku列表
        $closure(function ($position, $payload) use(&$list) {
            if ($position instanceof Attr) {
                $position = $position->getKey();
            }

            $list[] = compact('position', 'payload');
        });

        DB::transaction(function () use ($list) {
            // 清空sku记录
            $this->skus()->delete();

            // 插入sku记录
            foreach ($list as $value) {
                $sku = $this->skus()->create($value['payload']);
                $sku->attrs()->attach($value['position']);
            }
        });
    }
}