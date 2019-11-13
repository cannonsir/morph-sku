<?php

namespace Gtd\MorphSku\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface SkuContract
{
    /**
     * 获取所属产品
     *
     * @return MorphTo
     */
    public function producible(): MorphTo;

    /**
     * 获取属性键值
     *
     * @return BelongsToMany
     */
    public function attrs(): BelongsToMany;

    /**
     * 同步属性键值
     *
     * @param mixed ...$attrs
     * @return mixed
     */
    public function syncAttrs(...$attrs);

    /**
     * 分配属性键值
     *
     * @param mixed ...$attrs
     * @return mixed
     */
    public function assignAttrs(...$attrs);

    /**
     * 移除属性键值
     *
     * @param mixed ...$attrs
     * @return mixed
     */
    public function removeAttrs(...$attrs);
}