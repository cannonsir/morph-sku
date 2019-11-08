<?php

namespace Gtd\Product\Models;

use Gtd\Product\Exceptions\AttributeValueVerificationFailedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('product_sku.table_names.attributes'));
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function (self $self) {
            // 类型限制
            if (!in_array($self->attributes['type'], config('product_sku.attribute_types'), true)) {
                throw new \InvalidArgumentException('属性类型无效');
            }

            // 预设值类型限制
            $accepts = array_keys(config('product_sku.attribute_value_accepts'));
            if (!in_array($self->attributes['accept'], $accepts, true)) {
                throw new \InvalidArgumentException('预设值类型无效');
            }
        });
    }

    public function presetValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 预设值验证
     *
     * @param $value
     * @throws AttributeValueVerificationFailedException
     */
    public function presetVerify($value)
    {
        if (!$this->getAcceptClass()::presetVerify($value)) {
            throw new AttributeValueVerificationFailedException();
        }
    }

    /**
     * 获取预设值验证类
     *
     * @return mixed
     */
    public function getAcceptClass()
    {
        $accepts = config('product_sku.attribute_value_accepts');

        return $accepts[$this->accept];
    }

    /**
     * 批量添加预设值
     *
     * @param mixed ...$values
     */
    public function addPresetValues(...$values)
    {
        $models = array_map(function ($value) {
            $this->presetVerify($value);
            $type = $this->accept;

            return new AttributeValue(compact('type', 'value'));
        }, $values);

        $this->presetValues()->saveMany($models);
    }

    /*
     * 移除所有预设值
     */
    public function removeAllPresetValue()
    {
        $this->presetValues()->delete();
    }
}